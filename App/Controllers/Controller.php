<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/5/18
 * Time: 22:49
 */

namespace App\Controllers;

use Smarty;
use Slim\Http\Request;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Http\Request as IlluminateRequest;

class Controller
{
    protected $illuminateRequest;
    
    public function __construct($container)
    {
        /**
         * @var Request $request
         */
        $request = slim_app('request');
        $this->logRequest($request);
    }

    /**
     * @param Request|ServerRequestInterface $request
     * @param string $appendMessage
     */
    public static function logRequest($request, $appendMessage = '')
    {
        $serverParams = $request->getServerParams();
        // except the aliyun website monitor ip
        $ip = $serverParams['REMOTE_ADDR'];
        if (!in_array($ip, [
            '112.126.75.221',
            '42.96.189.63',
            '120.26.64.126',
            '182.92.69.212',
            '120.27.47.144',
            '121.43.107.174',
            '112.124.127.53',
            '112.126.73.56',
        ])) {
            $port = $serverParams['REMOTE_PORT'];
            $uri = $request->getUri();
            $userAgent = $request->getHeaderLine('HTTP_USER_AGENT');
            info("$ip:$port visit 【{$uri}】【{$userAgent}】{$appendMessage}");
        }
    }

    protected $smarty;

    public function getSmarty()
    {
        if (is_null($this->smarty)) {
            $this->smarty = new Smarty();
            $this->smarty->setTemplateDir(__DIR__ . '/../../resources/smarty_views');
            $this->smarty->setCompileDir(__DIR__ . '/../../storage/framework/smarty_views');
        }

        return $this->smarty;
    }
    
    public function getIlluminateRequest()
    {
        if (is_null($this->illuminateRequest)) {
            $this->illuminateRequest = IlluminateRequest::capture();
        }
        
        return $this->illuminateRequest;
    }

}