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
    protected $serverParams;

    public function __construct($container)
    {
        $this->request = slim_app('request');
        $this->logRequest($this->request);
        $this->serverParams = $this->request->getServerParams();
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
        ])
        ) {
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

    public function isMobile()
    {
        $serverParams = $this->serverParams;
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($serverParams['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($serverParams['HTTP_VIA'])) {
            return stristr($serverParams['HTTP_VIA'], "wap") ? true : false;// 找不到为false,否则为true
        }
        // 判断手机发送的客户端标志,兼容性有待提高
        if (isset ($serverParams['HTTP_USER_AGENT'])) {
            $clientKeywords = array(
                'mobile',
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientKeywords) . ")/i", strtolower($serverParams['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        if (isset ($serverParams['HTTP_ACCEPT'])) { // 协议法，因为有可能不准确，放到最后判断
            $accept = $serverParams['HTTP_ACCEPT'];
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($accept, 'vnd.wap.wml') !== false) &&
                (strpos($accept, 'text/html') === false || strpos($accept, 'vnd.wap.wml') < strpos($accept, 'text/html'))
            ) {
                return true;
            }
        }
        return false;
    }

}