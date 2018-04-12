<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/5/28
 * Time: 20:26
 */

namespace App\Http\Middleware;

use App\Models\User;
use Slim\Http\Request;
use Slim\Http\Response;

class RootAuthenticate extends Authenticate
{
    const ROOT_IDENT = ['admin', 'github-iinux'];
    
    public static function isLogin()
    {
        if (empty($_SESSION['user'])) {
            return false;
        }
        $ident = $_SESSION['user'];
        $user = User::where('ident', $ident)->first();
        if (is_null($user)) {
            unset($_SESSION['user']);
            return false;
        }
        if (!in_array($user->ident, self::ROOT_IDENT)) {
            return false;
        }
        return true;
    }
}