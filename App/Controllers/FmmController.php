<?php
/**
 * Created by PhpStorm.
 * User: qzhang
 * Date: 2019/2/7
 * Time: 7:43
 */

namespace App\Controllers;

use App\Libs\Curl;
use Predis\Client;
use Slim\Http\Request;
use Slim\Http\Response;

class FmmController extends Controller
{
    protected $redis;

    const REDIS_KEY_INDEX = 'fmm:index';
    const REDIS_KEY_TOKEN = 'fmm:key';

    public function __construct($container)
    {
        parent::__construct($container);
        $this->redis = new Client();
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return mixed
     */
    public function indexView($request, $response, $args)
    {

        $redisKey = self::REDIS_KEY_INDEX;
        $output = $this->getData($redisKey);

        $outputObj = json_decode($output);
        if (!isset($outputObj->data->lists)) {
            return $output;
        }

        $smarty = $this->getSmarty();
        $smarty->assign('lists', $outputObj->data->lists);
        $smarty->display('fmm.tpl');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return mixed
     */
    public function anchorsView($request, $response, $args)
    {
        $redisKey = "fmm:index:{$args['name']}";
        $output = $this->getData($redisKey, "{\"name\":\"{$args['name']}\"}");

        $outputObj = json_decode($output);
        if (!isset($outputObj->data->lists)) {
            return $output;
        }

        $smarty = $this->getSmarty();
        $smarty->assign('lists', $outputObj->data->lists);
        $smarty->display('fmm_anchors.tpl');
    }

    protected function getToken()
    {
        $token = $this->redis->get(self::REDIS_KEY_TOKEN);
        if (empty($token)) {
            $config = config('fmm');
            $output = $this->request($config['login_uri'], $config['auth']);
            $outputObj = json_decode($output);
            if (isset($outputObj->data->token)) {
                $token = $outputObj->data->token;
                $this->redis->set(self::REDIS_KEY_TOKEN, $token);
            } else {
                $token = '';
            }
        }
        return $token;
    }

    protected function request($url, $postFields = '')
    {
        preg_match("/https?:\/\/([\w\d\.]+)\/.*/", $url, $matches);
        $host = $matches[1];

        $config = config('fmm');
        if ($url == $config['login_uri']) {
            $token = 'access_token';
        } else {
            $token = $this->getToken();
        }

        $headers = [
            'Content: application/json',
            "token: {$token}",
            'Content-Type: application/json; charset=utf-8',
            "Host: $host",
            'User-Agent: okhttp/3.8.1',
            'Pragma: no-cache',
            'Cache-Control: no-cache',
        ];
        $curlSession = new Curl(false);
        $curlSession->addOpt(CURLOPT_HTTPHEADER, $headers);
        $curlSession->addOpt(CURLOPT_URL, $url);
        $curlSession->addOpt(CURLOPT_RETURNTRANSFER, 1);
        $curlSession->addOpt(CURLOPT_HEADER, 1);
        $curlSession->addOpt(CURLOPT_BINARYTRANSFER, true);
        //$curlSession->addOpt(CURLOPT_ENCODING, 'gzip,deflate');
        $curlSession->addOpt(CURLOPT_SSL_VERIFYPEER, 0);
        // $curlSession->addOpt(CURLOPT_SSL_VERIFYHOST, 0);
        $curlSession->addOpt(CURLOPT_TIMEOUT, 100);
        $curlSession->addOpt(CURLOPT_POST, 1);
        $curlSession->addOpt(CURLOPT_POSTFIELDS, $postFields);

        $output = $curlSession->exec();
        if ($output === false) {
            return 'Curl error: ' . $curlSession->getError();
        } else {
            // $info = $curlSession->getInfoOptNull();
            $headerSize = $curlSession->getInfo(CURLINFO_HEADER_SIZE);
            $header = substr($output, 0, $headerSize);
            $output = substr($output, $headerSize);
            $curlSession->close();

            return $output;
        }

    }

    protected function getData($redisKey, $postFields = "")
    {
        $output = $this->redis->get($redisKey);

        if (empty($output)) {
            $config = config('fmm');
            if ($redisKey == self::REDIS_KEY_INDEX) {
                $uri = $config['index_uri'];
            } else {
                $uri = $config['anchors_uri'];
            }
            $output = $this->request($uri, $postFields);

            if ($outputObj = json_decode($output)) {
                if (isset($outputObj->code) && $outputObj->code == -997) {
                    $this->redis->del(self::REDIS_KEY_TOKEN);
                } else {
                    $this->redis->set($redisKey, $output);
                    $this->redis->expire($redisKey, $config['key_expire_second']);
                }
            }
        }

        return $output;
    }
}