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

