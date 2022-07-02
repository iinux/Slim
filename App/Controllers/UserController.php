<?php

namespace App\Controllers;

use App\Models\User;
use Exception;
use Slim\Http\Request;
use Slim\Http\Response;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function index($request, $response, $args)
    {
        $users = User::all();
        return $response->withJson([
            'code' => 0,
            'data' => $users->toArray(),
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function store($request, $response, $args)
    {
        /**
         * @var User $user
         */
        $ident = $request->getParam('ident');

        $user = User::where('ident', $ident)->first();
        if ($user) {
            return $response->withJson([
                'code'    => 400,
                'message' => 'exist ident',
            ]);
        }
        $salt = str_random(5);
        $password = md5($request->getParam('password') . $salt);

        User::create([
            'ident'    => $ident,
            'salt'     => $salt,
            'password' => $password,
        ]);
        return $response->withJson(['code' => 0]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function update($request, $response, $args)
    {
        /**
         * @var User $user
         */
        $user = User::findOrFail($args['id']);
        $user->password = md5($request->getParam('password') . $user->salt);
        $user->save();
        return $response->withJson(['code' => 0]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function destroy($request, $response, $args)
    {
        /**
         * @var User $user
         */
        $user = User::findOrFail($args['id']);
        $user->delete();
        return $response->withJson(['code' => 0]);
    }
}