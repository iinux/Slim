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
use Slim\Http\Response;
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
            if (isset($serverParams['HTTP_REFERER'])) {
                $refer = $serverParams['HTTP_REFERER'];
                $refer = "【{$refer}】";
            } else {
                $refer = '';
            }
            info("$ip:$port visit 【{$uri}】【{$userAgent}】{$refer}{$appendMessage}");
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
                'wap',
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

    protected function pack(array $data)
    {
        $data = json_encode($data);
        if ($data === false) {
            return 'error(' . json_last_error() . '):' . json_last_error_msg();
        }
        $data = gzencode($data, 9);
        $data = base64_encode($data);
        return $data;
    }

    protected function unpack($data)
    {
        $data = base64_decode($data);
        $data = gzdecode($data);
        $data = json_decode($data, true);
        return $data;
    }

    protected function curl($url, $data = [])
    {
        if (env('G_PROXY')) {
            $data['postData'] = [
                'key'  => env('SECRET_KEY'),
                'data' => $this->pack([
                    'url'  => $url,
                    'data' => $data,
                ]),
            ];
            $url = env('G_PROXY') . '/api/common/g-proxy';
        }
        $serverParams = $this->serverParams;
        $headers = array(
            "Connection: {$serverParams['HTTP_CONNECTION']}",

            // sometime don't have cache-control
            // "Cache-Control: {$serverParams['HTTP_CACHE_CONTROL']}",

            "User-Agent: {$serverParams['HTTP_USER_AGENT']}",
            "Accept: {$serverParams['HTTP_ACCEPT']}",

            // can't use this line
            // "Accept-Encoding: {$serverParams['HTTP_ACCEPT_ENCODING']}",

            // "Accept-Language: {$serverParams['HTTP_ACCEPT_LANGUAGE']}",
            // 如果有浏览器的Accept-Language是en-US, 会返回立陶宛语，可能是因为IP的原因
            "Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7,zh-TW;q=0.6",
        );
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curlSession, CURLOPT_URL, $url);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, 1);//设置是将结果保存到字符串中还是输出到屏幕上，1表示将结果保存到字符串
        curl_setopt($curlSession, CURLOPT_HEADER, 1);//显示返回的Header区域内容
        // curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true) ;
        curl_setopt($curlSession, CURLOPT_ENCODING, 'gzip,deflate');
        if (empty(ini_get('open_basedir'))) {
            curl_setopt($curlSession, CURLOPT_FOLLOWLOCATION, true);//使用自动跳转
        }
        // $userAgent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)";
        // curl_setopt($curlSession, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        // curl_setopt($curlSession, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        // curl_setopt($curlSession, CURLOPT_REFERER, $ref);
        // curl_setopt($curlSession, CURLOPT_COOKIEFILE,$GLOBALS['cookie_file']); // 读取上面所储存的Cookie信息
        // curl_setopt($curlSession, CURLOPT_COOKIEJAR, $GLOBALS['cookie_file']); // 存放Cookie信息的文件名称
        // curl_setopt($curlSession, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环

        if (isset($data['postData'])) {
            // $data['postData'] = array ("username" => "bob","key" => "12345");
            // post数据
            curl_setopt($curlSession, CURLOPT_POST, 1);
            // post的变量
            curl_setopt($curlSession, CURLOPT_POSTFIELDS, $data['postData']);
        }

        $output = curl_exec($curlSession);
        // $info = curl_getinfo($curlSession);
        $headerSize = curl_getinfo($curlSession, CURLINFO_HEADER_SIZE);
        $header = substr($output, 0, $headerSize);
        $output = substr($output, $headerSize);
        if (curl_errno($curlSession)) {
            return 'Curl error: ' . curl_error($curlSession);
        }
        curl_close($curlSession);

        // $output = gzdecode($output);
        if (env('G_PROXY')) {
            $data = json_decode($output);
            $output = $this->unpack($data->data)['result'];
        }

        return $output;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function gProxyServer($request, $response, $args)
    {
        $data = $request->getParam('data');
        $data = $this->unpack($data);

        $curlData = $this->curl($data['url'], $data['data']);

        return $response->withJson([
            'code' => 0,
            'data' => $this->pack([
                'result' => $curlData,
            ]),
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function remoteCurlServer($request, $response, $args)
    {
        $data = $request->getParam('data');
        $data = $this->unpack($data);

        $ch = new Curl();
        $ch->setOptArray($data['optArray']);

        $curlResponse = $ch->exec();
        if ($curlResponse === false) {
            return $response->withJson([
                'code' => -1,
                'data' => $this->pack([
                    'error' => $ch->getError(),
                ]),
            ]);
        }
        $responseInfo = $ch->getInfoOptNull();
        $headerSize = $ch->getInfo(CURLINFO_HEADER_SIZE);
        $ch->close();

        if (json_encode($curlResponse) === false) {
            $responseBase64 = true;
        } else {
            $responseBase64 = false;
        }

        return $response->withJson([
            'code' => 0,
            'data' => $this->pack([
                'responseBase64' => $responseBase64,
                'response'       => $responseBase64 ? base64_encode($curlResponse) : $curlResponse,
                'responseInfo'   => $responseInfo,
                'headerSize'     => $headerSize,
            ]),
        ]);
    }

}

class Curl
{
    protected $optArray = [];
    protected $ch;

    function __construct()
    {
        $this->ch = curl_init();
    }

    function addOpt($option, $value)
    {
        $this->optArray[$option] = $value;
    }

    function setOptArray($optArray)
    {
        $this->optArray = $optArray;
    }

    function close()
    {
        curl_close($this->ch);
    }

    function getError()
    {
        return curl_error($this->ch);
    }

    function getInfo($opt = null)
    {
        return curl_getinfo($this->ch, $opt);
    }

    function getInfoOptNull()
    {
        return curl_getinfo($this->ch);
    }

    function exec()
    {
        foreach ($this->optArray as $option => $value) {
            curl_setopt($this->ch, $option, $value);
        }
        return curl_exec($this->ch);
    }
}
