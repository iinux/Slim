<?php

namespace App\Controllers;

use App\Http\Middleware\Authenticate;
use App\Models\Link;
use App\Models\Password;
use Slim\Http\ServerRequest;
use Slim\Http\Response;
use Smarty\Exception as SmartyException;

class QxwController extends Controller
{
    /**
     * @param ServerRequest $request
     * @param Response $response
     * @throws SmartyException
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
        return $response;
    }

    /**
     * @param ServerRequest $request
     * @param Response $response
     * @return Response
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
     * @param ServerRequest $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function getLinkContent($request, $response, $args)
    {
        /**
         * @var Link $link
         */
        $link = Link::findOrFail($args['id']);
        return $response->write($link->content);
    }

    /**
     * @param ServerRequest $request
     * @param Response $response
     * @param array $args
     * @return Response
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
     * @param ServerRequest $request
     * @param Response $response
     * @return Response
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
