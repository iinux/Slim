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
    
    public function __construct()
    {
        /**
         * @var Request $request
         */
        $request = slim_app('request');
        $serverParams = $request->getServerParams();
        info("{$serverParams['REMOTE_ADDR']}:{$serverParams['REMOTE_PORT']} visit {$request->getUri()}");
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