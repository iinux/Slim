<?php
/**
 * Created by PhpStorm.
 * User: qzhang
 * Date: 2018/9/30
 * Time: 13:52
 */

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class VueController extends Controller
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function indexView($request, $response, $args)
    {
        $smarty = $this->getSmarty();
        $smarty->display('vue.tpl');
        return;
    }

}