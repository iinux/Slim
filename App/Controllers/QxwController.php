<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/8/10
 * Time: 20:32
 */

namespace App\Controllers;

use App\Models\Link;
use App\Models\Password;
use Slim\Http\Request;
use Slim\Http\Response;

class QxwController extends Controller
{
    /**
     * @param Request $request
     * @param Response $response
     */
    public function indexView($request, $response)
    {
        $links = Link::orderBy('time', 'desc')->get();
        $passwords = Password::orderBy('time', 'desc')->get();
        $smarty = $this->getSmarty();
        $smarty->assign('links', $links);
        $smarty->assign('passwords', $passwords);
        $smarty->display('qxw.tpl');
    }

}