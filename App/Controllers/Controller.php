<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/5/18
 * Time: 22:49
 */

namespace App\Controllers;

use Smarty;

class Controller
{
    public function __construct()
    {
        session_start();
    }

    protected $smarty;

    public function getSmarty()
    {
        if (is_null($this->smarty)) {
            $this->smarty = new Smarty();
            $this->smarty->setTemplateDir(__DIR__ . '/../../resources/smarty_views');
            $this->smarty->setCompileDir(__DIR__ . '/../../storage/framework/smarty_views');
        }

        return $this->smarty;
    }

}