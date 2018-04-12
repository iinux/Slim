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

class Authenticate
{
    /**
     * @param Request $request
     * @param Response $response
     * @param $next
     * @return mixed
     */
    public function handle($request, $response, $next)
    {
        if (!static::isLogin()) {
            if ($request->getHeaderLine('X-Requested-With') == 'XMLHttpRequest') {
                return $response->withStatus(403);
            } else {
                $_SESSION['intended.url'] = $request->getUri();
                return $response->withRedirect('/auth/login');
            }
        }
        $response = $next($request, $response);

        return $response;
    }
    
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
        return true;
    }
}