<?php

namespace App\Controllers;

use App\Models\WeddingUser;
use Slim\Http\ServerRequest;
use Slim\Http\Response;

class WeddingController extends Controller
{
    /**
     * @param ServerRequest $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function userStore($request, $response, $args)
    {
        $params = $request->getParams();
        $params['ip'] = $request->getServerParams()['REMOTE_ADDR'];
        $params['user_agent'] = $request->getServerParams()['HTTP_USER_AGENT'];
        $user = WeddingUser::create($params);
        return $response->withJson([
            'code' => 0,
            'data' => $user->toArray(),
        ]);
    }

}