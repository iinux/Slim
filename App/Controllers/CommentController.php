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
        if (empty($_SESSION['user'])) {
            return $response->withStatus(403);
        }
        $data = $request->getParams();
        $data['ip'] = $request->getServerParams()['REMOTE_ADDR'];
        $comment = Comment::create($data);
        return $response->withJson(['code'=>0]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function update($request, $response, $args)
    {
        if (empty($_SESSION['user'])) {
            return $response->withStatus(403);
        }
        /**
         * @var Comment $comment
         */
        $comment = Comment::findOrFail($args['id']);
        $comment->content = $request->getParam('content');
        $comment->save();
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

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function showView($request, $response, $args)
    {
        $commentId = $args['id'];
        
        $comment = Comment::findOrFail($commentId);
        
        $smarty = $this->getSmarty();
        $smarty->assign('id', $commentId);
        $smarty->assign('comment', $comment);
        $smarty->display('comments_edit.tpl');
    }
}