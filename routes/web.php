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

$app->get('/', '\App\Controllers\IndexController:index');
$app->get('/comments/add', '\App\Controllers\CommentController:storeView');
$app->get('/comments/{id}', '\App\Controllers\CommentController:showView');

$app->get('/auth/login', '\App\Controllers\AuthController:loginView');
$app->get('/auth/logout', '\App\Controllers\AuthController:logoutView');
$app->post('/auth/check', '\App\Controllers\AuthController:checkView');

$app->get('/search', '\App\Controllers\GoogleController:indexView');
$app->get('/url', '\App\Controllers\GoogleController:url');
$app->post('/search', '\App\Controllers\GoogleController:searchView');
