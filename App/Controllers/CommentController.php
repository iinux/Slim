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
        $comment = Comment::create($request->getParams());
        return $response->withJson(['code'=>0]);
    }

}