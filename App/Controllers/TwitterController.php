<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/12/29
 * Time: 21:20
 */

namespace App\Controllers;


class TwitterController extends Controller
{
    public function trump()
    {
        $output = $this->curl('http://twitter.com/realdonaldtrump');
        return $output;
    }
}