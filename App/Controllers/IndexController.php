<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/5/18
 * Time: 22:49
 */

namespace App\Controllers;


use App\Models\Comment;

class IndexController extends Controller
{
    public function index($request, $response)
    {
        $comments = Comment::all();
        $smarty = $this->getSmarty();
        $smarty->assign('comments', $comments);
        $smarty->display('comments.tpl');
    }

}