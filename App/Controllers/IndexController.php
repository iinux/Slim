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
        $comments = Comment::orderBy('updated_at', 'desc')->get();
        $smarty = $this->getSmarty();
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

}