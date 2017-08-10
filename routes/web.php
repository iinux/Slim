<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/5/21
 * Time: 22:17
 */

$app->get('/hello/{name}', function ($request, $response, $args) {
    $response->write("Hello, " . $args['name']);
    return $response;
});

$app->get('/statistic.js', '\App\Controllers\IndexController:statisticJs');

$app->group('/comments', function () use ($app) {
    $app->get('/add', '\App\Controllers\CommentController:storeView');
    $app->get('/{id}', '\App\Controllers\CommentController:showView');
})->add('mw.auth');

$app->get('/auth/login', '\App\Controllers\AuthController:loginView');
$app->get('/auth/logout', '\App\Controllers\AuthController:logoutView');
$app->post('/auth/check', '\App\Controllers\AuthController:checkView');

$app->group('', function () use ($app) {
    $app->get('/', '\App\Controllers\IndexController:index');
    $app->get('/logs', '\App\Controllers\LogViewerController:index');

    $app->get('/search', '\App\Controllers\GoogleController:indexView');
    $app->get('/url', '\App\Controllers\GoogleController:url');
    $app->post('/url', '\App\Controllers\GoogleController:url');
    $app->post('/search', '\App\Controllers\GoogleController:searchView');
})->add('mw.auth');
