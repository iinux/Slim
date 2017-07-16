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
    /**
     * @var Request $request
     */
    protected $illuminateRequest;
    protected $request;
    
    public function __construct($container)
    {
        $this->request = slim_app('request');
        $this->logRequest($this->request);
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
            // refer https://help.aliyun.com/knowledge_detail/38829.html
            // 杭州监控节点机器
            '112.124.127.224',
            '112.124.127.44',
            '112.124.127.64',
            '112.124.127.53',
            '121.43.105.176',
            '120.26.216.168',
            '120.26.64.126',
            '121.43.107.174',
            '121.41.117.242',
            '121.40.130.38',
            '121.41.112.148',
            // 青岛监控节点服务器
            '115.29.112.222',
            '115.28.203.70',
            '42.96.189.63',
            '115.29.113.101',
            '120.27.40.113',
            '115.28.171.22',
            '115.28.189.208',
            '121.42.196.232',
            '115.28.26.13',
            '120.27.47.144',
            '120.27.47.33',
            // 北京监控节点服务器
            '112.126.74.55',
            '182.92.148.207',
            '182.92.1.233',
            '112.126.73.56',
            '123.56.138.37',
            '123.57.10.133',
            '112.126.75.174',
            '182.92.157.118',
            '112.126.75.221',
            '182.92.69.212',
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