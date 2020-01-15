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

        $chartData = [];
        foreach ($chPrices as $chPrice) {
            $chartData[] = [
                'time'  => $chPrice->created_at->format("Y-m-d H:i"),
                'price' => $chPrice->price,
                'line'  => "{$chPrice->from}{$chPrice->to}",
            ];
        }
        $smarty->assign('chartData', json_encode($chartData));

        $smarty->display('ch_price_one.tpl');
    }
}
