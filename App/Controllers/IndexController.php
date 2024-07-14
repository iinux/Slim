<?php

namespace App\Controllers;

use Slim\Http\ServerRequest;
use Slim\Http\Response;
use App\Models\Comment;
use Smarty\Exception as SmartyException;

class IndexController extends Controller
{
    /**
     * @param ServerRequest $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws SmartyException
     */
    public function index($request, $response, $args)
    {
        /*if (!auth_user()->hasRight('index')) {
            return $response->write('forbidden');
        }*/
        $comments = Comment::orderBy('updated_at', 'desc')->get();
        $smarty = $this->getSmarty();
        $shuffleText = 'Shuffle';
        $shuffleUri = '/?shuffle=1';
        if ($request->getParam('shuffle')) {
            $comments = $comments->shuffle();
            $shuffleText = 'Order';
            $shuffleUri = '/';
        }
        $smarty->assign('shuffleText', $shuffleText);
        $smarty->assign('shuffleUri', $shuffleUri);
        $smarty->assign('comments', $comments);
        $smarty->display('comments.tpl');
        return $response;
    }

    /**
     * @param ServerRequest $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function testPost($request, $response, $args)
    {
        $response->withJson(['data' => $request->getParam('content')]);
        return $response;
    }

    /**
     * @param ServerRequest $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function statisticJs($request, $response, $args)
    {
        $response->write("console.log('welcome');");
        return $response;
    }

}
