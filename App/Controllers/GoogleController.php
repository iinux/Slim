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
        if (count($request->getParams()) >= 1){
            $response->write($this->curlGoogle(http_build_query($request->getParams())));
            return ;
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

        $url = "http://www.google.com.hk/search?" . $queryString;
        $thisHeader = array(
            "Accept-Language: zh-CN,zh;q=0.8"
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $thisHeader);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是将结果保存到字符串中还是输出到屏幕上，1表示将结果保存到字符串
        curl_setopt($ch, CURLOPT_HEADER, 0);//显示返回的Header区域内容
        //curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ;
        //curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);//使用自动跳转
        //curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        //curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        //curl_setopt($ch, CURLOPT_REFERER, $ref);
        //curl_setopt($ch, CURLOPT_COOKIEFILE,$GLOBALS['cookie_file']); // 读取上面所储存的Cookie信息
        //curl_setopt($ch, CURLOPT_COOKIEJAR, $GLOBALS['cookie_file']); // 存放Cookie信息的文件名称
        //curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        //if (curl_errno($curl)) {
        //	echo 'Errno'.curl_error($curl);
        //}

        //$post_data = array ("username" => "bob","key" => "12345");
        // post数据
        //curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

        $output = curl_exec($ch);
        //$info = curl_getinfo($ch);
        curl_close($ch);

        $output = str_replace('/url?q=', '', $output);
        $output = str_replace('search?', 'search.php?', $output);
        $output = str_replace('action="/search"', 'action="/search.php"', $output);

        $output = mb_convert_encoding($output, 'utf-8', 'gbk'); //加上这行

        //返回获得的数据
        //$str = gzdecode($output);
        return $output;
    }
}