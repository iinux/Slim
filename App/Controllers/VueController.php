<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use SmartyException;

class VueController extends Controller
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     * @throws SmartyException
     */
    public function indexView($request, $response, $args)
    {
        $smarty = $this->getSmarty();
        $smarty->display('vue.tpl');
        return;
    }

}