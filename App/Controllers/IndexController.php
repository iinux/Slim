<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/5/18
 * Time: 22:49
 */

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Comment;

class IndexController extends Controller
{
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return mixed
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
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return mixed
     */
    public function testPost($request, $response, $args)
    {
        $response->withJson(['data' => $request->getParam('content')]);
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return mixed
     */
    public function statisticJs($request, $response, $args)
    {
        $response->write("console.log('welcome');");
    }

}
