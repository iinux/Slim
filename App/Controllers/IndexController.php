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
    public function index()
    {
        $comments = Comment::all();
        dd($comments);
        $smarty = $this->getSmarty();
        $smarty->assign('comments', ['a','b']);
        $smarty->display('comments.tpl');
    }

}