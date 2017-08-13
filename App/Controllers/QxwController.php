<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/8/10
 * Time: 20:32
 */

namespace App\Controllers;

use App\Http\Middleware\Authenticate;
use App\Models\Link;
use App\Models\Password;
use Slim\Http\Request;
use Slim\Http\Response;

class QxwController extends Controller
{
    /**
     * @param Request $request
     * @param Response $response
     */
    public function indexView($request, $response)
    {
        $links = Link::orderBy('updated_at', 'desc')->get();
        if (Authenticate::isLogin()) {
            $passwords = Password::orderBy('updated_at', 'desc')->get();
        } else {
            $passwords = [];
        }
        $smarty = $this->getSmarty();
        $smarty->assign('links', $links);
        $smarty->assign('passwords', $passwords);

        $linkId = $request->getParam('linkId');
        if ($linkId) {
            $smarty->assign('link', Link::findOrFail($linkId));
        }
        $passwordId = $request->getParam('passwordId');
        if ($passwordId && Authenticate::isLogin()) {
            $smarty->assign('password', Password::firstOrFail($passwordId));
        }

        $smarty->display('qxw.tpl');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function storeLink($request, $response)
    {
        $data = $request->getParams();
        $data['ip'] = $request->getServerParams()['REMOTE_ADDR'];
        $data['user_agent'] = $request->getServerParams()['HTTP_USER_AGENT'];
        $data['category'] = 1;
        $link = Link::create($data);
        return $response->withJson(['code'=>0]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function updateLink($request, $response, $args)
    {
        /**
         * @var Link $link
         */
        $link = Link::findOrFail($args['id']);
        $link->content = $request->getParam('content');
        $link->link = $request->getParam('link');
        $link->misc = $request->getParam('misc');
        $link->save();
        return $response->withJson(['code'=>0]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function storePassword($request, $response)
    {
        $data = $request->getParams();
        $data['password'] = $data['content'];
        unset($data['content']);
        unset($data['link']);
        $data['ip'] = $request->getServerParams()['REMOTE_ADDR'];
        $data['user_agent'] = $request->getServerParams()['HTTP_USER_AGENT'];
        $password = Password::create($data);
        return $response->withJson(['code'=>0]);
    }
}