<?php

namespace App\Controllers;

use App\Libs\Curl;
use Predis\Client;
use Slim\Http\ServerRequest;
use Slim\Http\Response;
use Smarty\Exception as SmartyException;

class FsController extends Controller
{
    /**
     * @param ServerRequest $request
     * @param Response $response
     * @param $args
     * @return false|string|void
     * @throws SmartyException
     */
    public function indexView($request, $response, $args)
    {
        $config = config('fs');
        $url = $config['config_json_uri'];
        preg_match("/https?:\/\/([\w\d\.\-]+)\/.*/", $url, $matches);

        $redisKey = 'fs:config_json';
        $redis = new Client();
        $output = $redis->get($redisKey);

        if (empty($output)) {
            $curlSession = new Curl(false);
            $curlSession->addOpt(CURLOPT_URL, $url);
            $curlSession->addOpt(CURLOPT_RETURNTRANSFER, 1);
            $curlSession->addOpt(CURLOPT_HEADER, 1);
            $curlSession->addOpt(CURLOPT_BINARYTRANSFER, true);
            $curlSession->addOpt(CURLOPT_SSL_VERIFYPEER, 0);
            $curlSession->addOpt(CURLOPT_TIMEOUT, 100);

            $output = $curlSession->exec();
            if ($output === false) {
                return 'Curl error: ' . $curlSession->getError();
            } else {
                $headerSize = $curlSession->getInfo(CURLINFO_HEADER_SIZE);
                $output = substr($output, $headerSize);
                $curlSession->close();

                if (json_decode($output)) {
                    $redis->set($redisKey, $output);
                    $redis->expire($redisKey, $config['key_expire_second']);
                }
            }
        }

        $outputObj = json_decode($output);

        if (!isset($outputObj->outbounds[0]->settings->vnext[0]->users[0]->id)) {
            return $output;
        }

        $uid = $outputObj->outbounds[0]->settings->vnext[0]->users[0]->id;

        $smarty = $this->getSmarty();
        $smarty->assign('uid', $uid);
        $smarty->assign('initVmess', config('fs.init_vmess'));
        $smarty->display('fs.tpl');
    }

}