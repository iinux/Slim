<?php

namespace App\Controllers;

use Slim\Http\ServerRequest;
use Slim\Http\Response;
use Smarty\Exception as SmartyException;

class VueController extends Controller
{
    /**
     * @param ServerRequest $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws SmartyException
     */
    public function indexView($request, $response, $args)
    {
        $smarty = $this->getSmarty();
        $smarty->display('vue.tpl');
        return $response;
    }

}