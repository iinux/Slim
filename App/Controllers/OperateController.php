<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/12/21
 * Time: 23:09
 */

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class OperateController extends Controller
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function pull($request, $response, $args)
    {
        require_once __DIR__.'/../../update_code.php';
    }
}