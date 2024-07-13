<?php

namespace App\Controllers;

use Slim\Http\Message;
use Slim\Http\Request;
use Slim\Http\Response;
use SmartyException;

class GoogleController extends Controller
{
    protected $gDomain;

    public function __construct($container)
    {
        parent::__construct($container);
        $this->gDomain = 'https://' . env('GOOGLE_DOMAIN', 'www.google.com.hk');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     * @throws SmartyException
     */
    public function indexView($request, $response, $args)
    {
        if (count($request->getParams()) >= 1) {
            $response->write($this->curlGoogle(http_build_query($request->getParams()), $request->getUri()->getPath()));
            return;
        }
        $smarty = $this->getSmarty();
        $smarty->assign('title', 'SearchEngine');
        $smarty->assign('formAction', '/search');
        $smarty->assign('useGoogleFont', false);
        $smarty->assign('dns', false);
        if ($this->isMobile()) {
            $smarty->assign('headerTitle', 'Perorsoft Search');
            $smarty->display('google_index.m.tpl');
        } else {
            $smarty->assign('headerTitle', 'Perorsoft Search Engine(PHP Version)');
            $smarty->display('google_index.tpl');
        }
        return;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response|Message
     */
    public function completeSearch($request, $response, $args)
    {
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->write($this->curlGoogle(http_build_query($request->getParams()), $request->getUri()->getPath()));
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response|void
     */
    public function searchView($request, $response, $args)
    {
        $q = $request->getParam('q');
        if (!empty($q)) {
            return $response->withRedirect("https://www.bing.com/search?q=$q");
            return $response->withRedirect("https://www.baidu.com/s?wd=$q");
        }
        $q = str_replace(' ', '+', $q);
        $response->write($this->curlGoogle('q=' . $q));
        // test use baidu
        // $response->write($this->curlGoogle('wd=' . $q, '/s'));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function null($request, $response, $args)
    {
        $response->write('null');
        return;
    }

    protected function curlGoogle($queryString, $uriPath = '/search')
    {
        $log = $_SERVER['REMOTE_ADDR'] . ' ' . $_SERVER['REMOTE_PORT'] . ' ' . $queryString;
        $log = urldecode($log);
        info($log);

        $url = "{$this->gDomain}$uriPath?" . $queryString;

        $output = $this->curl($url);

        $output = str_replace('<a href="', '<a target="_blank" href="', $output);
        // $output = str_replace('https://encrypted-tbn0.gstatic.com/', '/https=encrypted-tbn0.gstatic.com-', $output);
        $output = str_replace('</body>', $this->hideImg() . '</body>', $output);

        $forceHttps = [
            'www.epochtimes.com',
        ];
        foreach ($forceHttps as $item) {
            $output = str_replace("http://$item", "https://$item", $output);
        }

        // $output = mb_convert_encoding($output, 'utf-8', 'gbk'); //加上这行

        // 返回获得的数据
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
        if (empty($q)) {
            $q = $request->getParam('url');
        }
        info("google search click redirect to $q");

        $proxyUrlPrefix = [
            'https://zh.wikipedia.org/',
            'https://zh.m.wikipedia.org/',
        ];
        foreach ($proxyUrlPrefix as $item) {
            if (strpos($q, $item) === 0) {
                return $this->curl($q);
            }
        }

        return $response->withRedirect($q);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function getLogos($request, $response, $args)
    {
        $file = __INDEX_PHP_DIR__ . $request->getUri()->getPath();
        $output = $this->getOutput($request, $file);
        $response->withHeader('Content-Type', 'image/png');
        $response->write($output);

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Message|Response
     */
    public function getStaticImage($request, $response, $args)
    {
        $file = __INDEX_PHP_DIR__ . $request->getUri()->getPath();
        $url = "{$args['proto']}://{$args['domain']}" . '/images?q=' . $request->getParam('q');

        if (file_exists($file)) {
            $output = file_get_contents($file);
        } else {
            $output = $this->curl($url);

            $filePath = substr($file, 0, strrpos($file, "/"));
            if ($filePath && !is_dir($filePath)) {
                mkdir($filePath, 0777, true);
            }

            file_put_contents($file, $output);
        }
        $response = $response->withHeader('Content-Type', 'image/png');
        $response->write($output);

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Message|Response
     */
    public function xjs($request, $response, $args)
    {
        $file = __INDEX_PHP_DIR__ . $request->getUri()->getPath();
        $file .= '.js';

        if (file_exists($file)) {
            $output = file_get_contents($file);
        } else {
            $output = $this->getOutput($request, $file);
        }
        $response = $response->withHeader('Content-Type', 'text/javascript');
        $response->write($output);

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Message|Response
     */
    public function dnsResult($request, $response, $args)
    {
        $q = $request->getParam('q');
        $output = $this->curl('https://dns.google.com/resolve?name=' . base64_decode($q));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->write($output);
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     * @throws SmartyException
     */
    public function dnsView($request, $response, $args)
    {
        $smarty = $this->getSmarty();
        $smarty->assign('title', 'DNS');
        $smarty->assign('formAction', '/dns-result');
        $smarty->assign('useGoogleFont', false);
        $smarty->assign('headerTitle', 'DNS');
        $smarty->assign('dns', true);
        if ($this->isMobile()) {
            $smarty->display('google_index.m.tpl');
        } else {
            $smarty->display('google_index.tpl');
        }
    }

    public function hideImg()
    {
        return '<script>window.onload=function(){' .
            'var img=document.getElementsByTagName("img")[document.getElementsByTagName("img").length-1];' .
            'if(img.src.indexOf("https://cdn.rawgit.com/000webhost/logo/")>=0){img.style.display="none";}' .
            '}</script>';
    }

    /**
     * @param Request $request
     * @param $file
     * @return false|mixed|string
     */
    public function getOutput(Request $request, $file)
    {
        $url = $this->gDomain . $request->getUri()->getPath();

        $output = $this->curl($url);

        $filePath = substr($file, 0, strrpos($file, "/"));

        if ($filePath && !is_dir($filePath)) {
            mkdir($filePath, 0777, true);
        }

        file_put_contents($file, $output);
        return $output;
    }
}
