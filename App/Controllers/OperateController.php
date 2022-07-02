<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class OperateController extends Controller
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function pull($request, $response, $args)
    {
        require_once __DIR__.'/../../update_code.php';
    }
}