<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/5/28
 * Time: 20:26
 */

namespace App\Http\Middleware;

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
        if (empty($_SESSION['user'])) {
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
}