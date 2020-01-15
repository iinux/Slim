<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/8/10
 * Time: 20:32
 */

namespace App\Controllers;

use App\Http\Middleware\Authenticate;
use App\Models\ChPrice;
use App\Models\Link;
use App\Models\Password;
use Slim\Http\Request;
use Slim\Http\Response;

class ChPriceController extends Controller
{
    /**
     * @param Request $request
     * @param Response $response
     */
    public function indexView($request, $response)
    {
        $chPrices = ChPrice::where('status', ChPrice::STATUS_NEW)->orderBy('date', 'asc')->get();
        $smarty = $this->getSmarty();
        $smarty->assign('chPrices', $chPrices);

        $smarty->display('ch_price.tpl');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function oneView($request, $response, $args)
    {
        $chPrices = ChPrice::where('from', $request->getQueryParam('from'))
            ->where('to', $request->getQueryParam('to'))
            ->where('date', $request->getQueryParam('date'))
            ->orderBy('created_at', 'asc')->get();
        $smarty = $this->getSmarty();
        $smarty->assign('chPrices', $chPrices);

        $smarty->display('ch_price_one.tpl');
    }
}
