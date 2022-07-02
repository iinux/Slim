<?php

namespace App\Http\Middleware;

use App\Models\User;
use Slim\Http\Request;
use Slim\Http\Response;

class SecretKeyAuthenticate
{
    /**
     * @param Request $request
     * @param Response $response
     * @param $next
     * @return mixed
     */
    public function handle($request, $response, $next)
    {
        $key = env('SECRET_KEY');
        if (empty($key) || $request->getParam('key') != $key) {
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