<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/5/18
 * Time: 22:49
 */

namespace App\Controllers;


class IndexController extends Controller
{
    public function index()
    {
        $smarty = $this->getSmarty();
        $smarty->assign('name', 'Jerry');
        $smarty->display('smarty_test.tpl');
    }

}