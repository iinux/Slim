<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/5/26
 * Time: 21:23
 */

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class GoogleController extends Controller
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function indexView($request, $response, $args)
    {
        if (count($request->getParams()) >= 1) {
            $response->write($this->curlGoogle(http_build_query($request->getParams())));
            return;
        }
        $smarty = $this->getSmarty();
        $smarty->assign('title', 'SearchEngine');
        $smarty->assign('headerTitle', 'Perorsoft Search Engine(PHP Version)');
        $smarty->assign('useGoogleFont', false);
        $smarty->display('google_index.tpl');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function searchView($request, $response, $args)
    {
        $q = str_replace(' ', '+', $request->getParam('q'));
        $response->write($this->curlGoogle('q=' . $q));
    }

    protected function curlGoogle($queryString)
    {
        $log = $_SERVER['REMOTE_ADDR'] . ' ' . $_SERVER['REMOTE_PORT'] . ' ' . $queryString;
        $log = urldecode($log);
        info($log);

        $url = "https://www.google.com.hk/search?" . $queryString;

        $output = $this->curl($url);

        $output = str_replace('<a href="', '<a target="_blank" href="', $output);
        $output = str_replace('http://mingshen.online/images/branding/googlelogo/2x/googlelogo_color_120x44dp.png',
            '', $output);

        // $output = mb_convert_encoding($output, 'utf-8', 'gbk'); //加上这行

        // 返回获得的数据
        return $output;
    }

    protected function curl($url)
    {
        $serverParams = $this->request->getServerParams();
        $headers = array(
            "Connection: {$serverParams['HTTP_CONNECTION']}",

            // sometime don't have cache-control
            // "Cache-Control: {$serverParams['HTTP_CACHE_CONTROL']}",

            "User-Agent: {$serverParams['HTTP_USER_AGENT']}",
            "Accept: {$serverParams['HTTP_ACCEPT']}",

            // can't use this line
            // "Accept-Encoding: {$serverParams['HTTP_ACCEPT_ENCODING']}",

            "Accept-Language: {$serverParams['HTTP_ACCEPT_LANGUAGE']}",
        );
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curlSession, CURLOPT_URL, $url);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, 1);//设置是将结果保存到字符串中还是输出到屏幕上，1表示将结果保存到字符串
        curl_setopt($curlSession, CURLOPT_HEADER, 1);//显示返回的Header区域内容
        // curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true) ;
        curl_setopt($curlSession, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($curlSession, CURLOPT_FOLLOWLOCATION, true);//使用自动跳转
        // $userAgent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)";
        // curl_setopt($curlSession, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        // curl_setopt($curlSession, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        // curl_setopt($curlSession, CURLOPT_REFERER, $ref);
        // curl_setopt($curlSession, CURLOPT_COOKIEFILE,$GLOBALS['cookie_file']); // 读取上面所储存的Cookie信息
        // curl_setopt($curlSession, CURLOPT_COOKIEJAR, $GLOBALS['cookie_file']); // 存放Cookie信息的文件名称
        // curl_setopt($curlSession, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环

        // $postData = array ("username" => "bob","key" => "12345");
        // post数据
        // curl_setopt($curlSession, CURLOPT_POST, 1);
        // post的变量
        // curl_setopt($curlSession, CURLOPT_POSTFIELDS, $postData);

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

        return $output;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function url($request, $response, $args)
    {
        $q = $request->getParam('q');
        info("google search click redirect to $q");

        $proxyUrlPrefix = [
            'https://zh.wikipedia.org/zh-hans/',
        ];
        foreach ($proxyUrlPrefix as $item) {
            if (strpos($q, $item) === 0) {
                return $this->curl($q);
            }
        }

        return $response->withRedirect($q);
    }
}