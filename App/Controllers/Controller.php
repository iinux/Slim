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
        $serverParams = $request->getServerParams();
        // except the aliyun website monitor ip
        if (!in_array($serverParams['REMOTE_ADDR'], [
            '112.126.75.221',
            '42.96.189.63',
            '120.26.64.126',
            '182.92.69.212',
            '120.27.47.144',
            '121.43.107.174'
        ])) {
            info("{$serverParams['REMOTE_ADDR']}:{$serverParams['REMOTE_PORT']} visit {$request->getUri()}");
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