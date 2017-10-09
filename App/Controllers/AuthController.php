<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/5/23
 * Time: 21:16
 */

namespace App\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        try {
            $user = User::where('ident', $ident)->firstOrFail();
            if (md5($password . $user->salt) == $user->password) {
                $_SESSION['user'] = $ident;
                if (isset($_SESSION['intended.url'])) {
                    $intendedUrl = $_SESSION['intended.url'];
                    unset($_SESSION['intended.url']);
                    return $response->withRedirect($intendedUrl);
                } else {
                    return $response->withRedirect('/');
                }
            } else {
                $smarty = $this->getSmarty();
                $smarty->assign('errors', ['ident or password error']);
                $smarty->display('login.tpl');
            }
        } catch (ModelNotFoundException $e) {
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