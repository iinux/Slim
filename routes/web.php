<?php
/**
 * User: nalux
 * Date: 2017/5/21
 * Time: 22:17
 */

use Slim\Routing\RouteCollectorProxy;
use App\Http\Middleware\Authenticate;

$app->get('/hello/{name}', function ($request, $response, $args) {
    $response->write("Hello, " . $args['name'] . ', env: ' . env('APP_ENV'));
    return $response;
});

$app->get('/statistic.js', '\App\Controllers\IndexController:statisticJs');

$app->group('/comments', function (RouteCollectorProxy $group) use ($app) {
    $group->get('/add', '\App\Controllers\CommentController:storeView');
    $group->get('/{id}', '\App\Controllers\CommentController:showView');
})->add(new Authenticate());

$app->group('/operate', function (RouteCollectorProxy $group) use ($app) {
    $group->get('/pull', '\App\Controllers\OperateController:pull');
})->add(new Authenticate());

$app->get('/auth/login', '\App\Controllers\AuthController:loginView');
$app->get('/auth/oauth-login/github', '\App\Controllers\AuthController:githubOauth');
$app->get('/callback/oauth/github', '\App\Controllers\AuthController:githubOauthCallback');
$app->get('/auth/logout', '\App\Controllers\AuthController:logoutView');
$app->post('/auth/check', '\App\Controllers\AuthController:checkView');

$app->get('/search', '\App\Controllers\GoogleController:indexView');
$app->post('/search', '\App\Controllers\GoogleController:searchView');

$app->group('', function (RouteCollectorProxy $group) use ($app) {
    $group->get('/', '\App\Controllers\IndexController:index');
    $group->get('/logs', '\App\Controllers\LogViewerController:index');

    $group->get('/complete/search', '\App\Controllers\GoogleController:completeSearch');
    $group->get('/{proto}={domain}-images', '\App\Controllers\GoogleController:getStaticImage');
    $group->get('/url', '\App\Controllers\GoogleController:url');
    $group->post('/url', '\App\Controllers\GoogleController:url');

    $group->get('/trump', '\App\Controllers\TwitterController:trump');

    $group->get('/dns', '\App\Controllers\GoogleController:dnsView');
    $group->get('/dns-result', '\App\Controllers\GoogleController:dnsResult');
    $group->post('/dns-result', '\App\Controllers\GoogleController:dnsResult');

    $group->get('/fmm', '\App\Controllers\FmmController:indexView');
    $group->get('/fmm/anchors/{name}', '\App\Controllers\FmmController:anchorsView');
    $group->get('/fmm/player/{url:.*}', '\App\Controllers\FmmController:playerView');

    $group->get('/fs', '\App\Controllers\FsController:indexView');
})->add(Authenticate::class);

$app->get('/qxw', '\App\Controllers\QxwController:indexView');
$app->get('/eat', '\App\Controllers\EatController:which');
$app->get('/chPrice', '\App\Controllers\ChPriceController:indexView');
$app->get('/chPriceOne', '\App\Controllers\ChPriceController:oneView');

$app->get('/logos/doodles/2017/{fileName}.png', '\App\Controllers\GoogleController:getLogos');
$app->get('/logos/doodles/2018/{fileName}.png', '\App\Controllers\GoogleController:getLogos');
$app->get('/logos/doodles/2020/{fileName}.png', '\App\Controllers\GoogleController:getLogos');
$app->get('/images/branding/googlelogo/1x/{fileName}.png', '\App\Controllers\GoogleController:getLogos');
$app->get('/images/branding/googlelogo/2x/{fileName}.png', '\App\Controllers\GoogleController:getLogos');
$app->get('/images/branding/product/ico/{fileName}.ico', '\App\Controllers\GoogleController:getLogos');
$app->get('/images/hpp/{fileName}.gif', '\App\Controllers\GoogleController:getLogos');
$app->get('/images/{fileName}.webp', '\App\Controllers\GoogleController:getLogos');
$app->get('/images/searchbox/{fileName}.webp', '\App\Controllers\GoogleController:getLogos');
$app->get('/images/{fileName}.png', '\App\Controllers\GoogleController:getLogos');
$app->get('/images/icons/material/system/1x/{fileName}.png', '\App\Controllers\GoogleController:getLogos');
$app->get('/xjs/_/js/{s1}/{s2}/{s3}/{s4}/{s5}/{s6}/{s7}', '\App\Controllers\GoogleController:xjs');
$app->get('/xjs/_/js/{s1}/{s2}/{s3}/{s4}/{s5}/{s6}/{s7}/{s8}', '\App\Controllers\GoogleController:xjs');
$app->get('/xjs/_/js/{s1}/{s2}/{s3}/{s4}/{s5}/{s6}/{s7}/{s8}/{s9}', '\App\Controllers\GoogleController:xjs');
$app->post('/gen_204', '\App\Controllers\GoogleController:null');
$app->get('/gen_204', '\App\Controllers\GoogleController:null');
$app->get('/client_204', '\App\Controllers\GoogleController:null');
$app->get('/async/irc', '\App\Controllers\GoogleController:null');

$app->get('/vue', '\App\Controllers\VueController:indexView');
