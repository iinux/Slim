<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use App\Models\Comment;
use Smarty\Exception as SmartyException;

class CommentController extends Controller
{
    /**
     * @param ServerRequestInterface $request
     * @param Response $response
     * @return Response
     */
    public function store($request, $response)
    {
        $data = $request->getParams();
        $data['ip'] = $request->getServerParams()['REMOTE_ADDR'];
        $comment = Comment::create($data);
        return $response->withJson(['code'=>0]);
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function update($request, $response, $args)
    {
        /**
         * @var Comment $comment
         */
        $comment = Comment::findOrFail($args['id']);
        $comment->content = $request->getParam('content');
        $comment->save();
        return $response->withJson(['code'=>0]);
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response $response
     * @return Response
     * @throws SmartyException
     */
    public function storeView($request, $response)
    {
        $smarty = $this->getSmarty();
        $smarty->display('comments_add.tpl');
        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws SmartyException
     */
    public function showView($request, $response, $args)
    {
        $commentId = $args['id'];

        $comment = Comment::findOrFail($commentId);

        $smarty = $this->getSmarty();
        $smarty->assign('id', $commentId);
        $smarty->assign('comment', $comment);
        $smarty->display('comments_edit.tpl');
        return $response;
    }
}
