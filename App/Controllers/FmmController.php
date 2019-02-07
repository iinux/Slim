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
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return mixed
     */
    public function indexView($request, $response, $args)
    {
        $config = config('fmm');
        $host = preg_match("/https?:\/\/([\w\d\.]+)\/.*/", $config['index_uri'], $matches);

        $redisKey = 'fmm:index';
        $redis = new Client();
        $output = $redis->get($redisKey);

        if (empty($output)) {
            $url = $config['index_uri'];
            $headers = [
                'Content: application/json',
                "token: {$config['token']}",
                'Content-Type: application/json; charset=utf-8',
                "Host: $host",
                'User-Agent: okhttp/3.8.1',
                'Pragma: no-cache',
                'Cache-Control: no-cache',
            ];
            $curlSession = new Curl(false);
            $curlSession->addOpt(CURLOPT_HTTPHEADER, $headers);
            $curlSession->addOpt(CURLOPT_URL, $url);
            $curlSession->addOpt(CURLOPT_RETURNTRANSFER, 1);//设置是将结果保存到字符串中还是输出到屏幕上，1表示将结果保存到字符串
            $curlSession->addOpt(CURLOPT_HEADER, 1);//显示返回的Header区域内容
            $curlSession->addOpt(CURLOPT_BINARYTRANSFER, true);
            //$curlSession->addOpt(CURLOPT_ENCODING, 'gzip,deflate');
            $curlSession->addOpt(CURLOPT_SSL_VERIFYPEER, 0);
            // $curlSession->addOpt(CURLOPT_SSL_VERIFYHOST, 0);
            $curlSession->addOpt(CURLOPT_TIMEOUT, 100);
            $curlSession->addOpt(CURLOPT_POST, 1);

            $output = $curlSession->exec();
            if ($output === false) {
                return 'Curl error: ' . $curlSession->getError();
            }
            // $info = $curlSession->getInfoOptNull();
            $headerSize = $curlSession->getInfo(CURLINFO_HEADER_SIZE);
            $header = substr($output, 0, $headerSize);
            $output = substr($output, $headerSize);
            $curlSession->close();
            $redis->set($redisKey, $output);
            $redis->expire($redisKey, $config['key_expire_second']);
        }

        $output = json_decode($output);
        $smarty = $this->getSmarty();
        $smarty->assign('lists', $output->data->lists);
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
        $config = config('fmm');
        $host = preg_match("/https?:\/\/([\w\d\.]+)\/.*/", $config['index_uri'], $matches);

        $redisKey = "fmm:index:{$args['name']}";
        $redis = new Client();
        $output = $redis->get($redisKey);

        if (empty($output)) {
            $url = $config['anchors_uri'];
            $headers = [
                'Content: application/json',
                "token: {$config['token']}",
                'Content-Type: application/json; charset=utf-8',
                "Host: $host",
                'User-Agent: okhttp/3.8.1',
                'Pragma: no-cache',
                'Cache-Control: no-cache',
            ];
            $curlSession = new Curl(false);
            $curlSession->addOpt(CURLOPT_HTTPHEADER, $headers);
            $curlSession->addOpt(CURLOPT_URL, $url);
            $curlSession->addOpt(CURLOPT_RETURNTRANSFER, 1);//设置是将结果保存到字符串中还是输出到屏幕上，1表示将结果保存到字符串
            $curlSession->addOpt(CURLOPT_HEADER, 1);//显示返回的Header区域内容
            $curlSession->addOpt(CURLOPT_BINARYTRANSFER, true);
            //$curlSession->addOpt(CURLOPT_ENCODING, 'gzip,deflate');
            $curlSession->addOpt(CURLOPT_SSL_VERIFYPEER, 0);
            // $curlSession->addOpt(CURLOPT_SSL_VERIFYHOST, 0);
            $curlSession->addOpt(CURLOPT_TIMEOUT, 100);
            $curlSession->addOpt(CURLOPT_POST, 1);
            $curlSession->addOpt(CURLOPT_POSTFIELDS, "{\"name\":\"{$args['name']}\"}");

            $output = $curlSession->exec();
            if ($output === false) {
                return 'Curl error: ' . $curlSession->getError();
            }
            // $info = $curlSession->getInfoOptNull();
            $headerSize = $curlSession->getInfo(CURLINFO_HEADER_SIZE);
            $header = substr($output, 0, $headerSize);
            $output = substr($output, $headerSize);
            $curlSession->close();
            $redis->set($redisKey, $output);
            $redis->expire($redisKey, $config['key_expire_second']);
        }

        $output = json_decode($output);
        $smarty = $this->getSmarty();
        $smarty->assign('lists', $output->data->lists);
        $smarty->display('fmm_anchors.tpl');
    }


}