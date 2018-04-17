<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/5/21
 * Time: 22:17
 */

$app->get('/hello/{name}', function ($request, $response, $args) {
    $response->write("Hello, " . $args['name'] . ', env: ' . env('APP_ENV'));
    return $response;
});

$app->get('/statistic.js', '\App\Controllers\IndexController:statisticJs');

$app->group('/comments', function () use ($app) {
    $app->get('/add', '\App\Controllers\CommentController:storeView');
    $app->get('/{id}', '\App\Controllers\CommentController:showView');
})->add('mw.auth');

$app->group('/operate', function () use ($app) {
    $app->get('/pull', '\App\Controllers\OperateController:pull');
})->add('mw.auth');

$app->get('/auth/login', '\App\Controllers\AuthController:loginView');
$app->get('/auth/oauth-login/github', '\App\Controllers\AuthController:githubOauth');
$app->get('/callback/oauth/github', '\App\Controllers\AuthController:githubOauthCallback');
$app->get('/auth/logout', '\App\Controllers\AuthController:logoutView');
$app->post('/auth/check', '\App\Controllers\AuthController:checkView');

$app->group('', function () use ($app) {
    $app->get('/', '\App\Controllers\IndexController:index');
    $app->get('/logs', '\App\Controllers\LogViewerController:index');

    $app->get('/search', '\App\Controllers\GoogleController:indexView');
    $app->get('/complete/search', '\App\Controllers\GoogleController:completeSearch');
    $app->get('/{proto}={domain}-images', '\App\Controllers\GoogleController:getStaticImage');
    $app->get('/url', '\App\Controllers\GoogleController:url');
    $app->post('/url', '\App\Controllers\GoogleController:url');
    $app->post('/search', '\App\Controllers\GoogleController:searchView');

    $app->get('/trump', '\App\Controllers\TwitterController:trump');

    $app->get('/dns', '\App\Controllers\GoogleController:dnsView');
    $app->get('/dns-result', '\App\Controllers\GoogleController:dnsResult');
    $app->post('/dns-result', '\App\Controllers\GoogleController:dnsResult');
})->add('mw.auth');

$app->get('/qxw', '\App\Controllers\QxwController:indexView');
$app->get('/eat', '\App\Controllers\EatController:which');
$app->get('/logos/doodles/2017/{fileName}.png', '\App\Controllers\GoogleController:getLogos');
$app->get('/logos/doodles/2018/{fileName}.png', '\App\Controllers\GoogleController:getLogos');
$app->get('/images/branding/googlelogo/1x/{fileName}.png', '\App\Controllers\GoogleController:getLogos');
$app->get('/images/branding/product/ico/{fileName}.ico', '\App\Controllers\GoogleController:getLogos');
$app->get('/images/hpp/{fileName}.gif', '\App\Controllers\GoogleController:getLogos');
$app->get('/images/branding/googlelogo/2x/{fileName}.png', '\App\Controllers\GoogleController:getLogos');
$app->get('/images/{fileName}.webp', '\App\Controllers\GoogleController:getLogos');
$app->get('/images/{fileName}.png', '\App\Controllers\GoogleController:getLogos');
$app->get('/xjs/_/js/{s1}/{s2}/{s3}/{s4}/{s5}/{s6}/{s7}', '\App\Controllers\GoogleController:xjs');
$app->get('/xjs/_/js/{s1}/{s2}/{s3}/{s4}/{s5}/{s6}/{s7}/{s8}', '\App\Controllers\GoogleController:xjs');
$app->get('/xjs/_/js/{s1}/{s2}/{s3}/{s4}/{s5}/{s6}/{s7}/{s8}/{s9}', '\App\Controllers\GoogleController:xjs');
$app->post('/gen_204', '\App\Controllers\GoogleController:null');
$app->get('/gen_204', '\App\Controllers\GoogleController:null');
$app->get('/client_204', '\App\Controllers\GoogleController:null');
$app->get('/async/irc', '\App\Controllers\GoogleController:null');
