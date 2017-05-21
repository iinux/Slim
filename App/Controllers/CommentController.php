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

class CommentController extends Controller
{
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function store($request, $response)
    {
        $data = $request->getParams();
        $data['ip'] = $request->getServerParams()['REMOTE_ADDR'];
        $comment = Comment::create($data);
        return $response->withJson(['code'=>0]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function storeView($request, $response)
    {
        $smarty = $this->getSmarty();
        $smarty->display('comments_add.tpl');
    }

}