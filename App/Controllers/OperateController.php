<?php

namespace App\Controllers;

use Slim\Http\ServerRequest;
use Slim\Http\Response;

class OperateController extends Controller
{

    /**
     * @param ServerRequest $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function pull($request, $response, $args)
    {
        require_once __DIR__.'/../../update_code.php';
    }
}