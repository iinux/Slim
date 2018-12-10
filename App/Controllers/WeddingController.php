<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2018/12/10
 * Time: 20:41
 */

namespace App\Controllers;

use App\Models\WeddingUser;
use Slim\Http\Request;
use Slim\Http\Response;

class WeddingController extends Controller
{
    /**
     * @param Request $request
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