<?php

namespace App\Http\Middleware;

use App\Models\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Http\Response;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class Authenticate implements MiddlewareInterface
{
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

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        if (!static::isLogin()) {
            if ($request->getHeaderLine('X-Requested-With') == 'XMLHttpRequest') {
                return $response->withStatus(403);
            } else {
                $_SESSION['intended.url'] = $request->getUri();
                return $response->withRedirect('/auth/login');
            }
        }
        return $response;
    }
}