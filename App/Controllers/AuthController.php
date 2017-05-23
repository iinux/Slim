<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/5/23
 * Time: 21:16
 */

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class AuthController extends Controller
{
    public function loginView()
    {
        $smarty = $this->getSmarty();
        $smarty->display('login.tpl');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function checkView($request, $response)
    {
        $ident = $request->getParam('ident');
        $password = $request->getParam('password');
        if ($ident == 'admin' && $password == '98526') {
            $_SESSION['user'] = 'admin';
            return $response->withRedirect('/');
        } else {
            $smarty = $this->getSmarty();
            $smarty->assign('errors', ['ident or password error']);
            $smarty->display('login.tpl');
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function logoutView($request, $response)
    {
        unset($_SESSION['user']);
        return $response->withRedirect('/');
    }

}