<?php

namespace App\Controllers;

use Slim\Http\ServerRequest;
use Slim\Http\Response;
use Smarty\Exception as SmartyException;

class EatController extends Controller
{

    /**
     * @param ServerRequest $request
     * @param Response $response
     * @throws SmartyException
     */
    public function which($request, $response)
    {
        $smarty = $this->getSmarty();

        $allMain = ['米饭', '面条', '馒头'];
        $smarty->assign('allMain', $allMain);
        $smarty->assign('main', collect($allMain)->random());
        $allDish = ['茄子', '土豆丝', '西红柿炒鸡蛋', '凉菜', '油麦', '豆芽', '豆腐', '白菜', '菜花'];
        $smarty->assign('allDish', $allDish);
        $smarty->assign('dish', collect($allDish)->random());
        $allSoup = ['大米粥', '小米粥'];
        $smarty->assign('allSoup', $allSoup);
        $smarty->assign('soup', collect($allSoup)->random());

        $smarty->display('eat.tpl');
    }
}
