<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/5/21
 * Time: 23:11
 */

$app->group('/api/comments', function () use ($app) {
    $app->post('', '\App\Controllers\CommentController:store');
    $app->put('/{id}', '\App\Controllers\CommentController:update');
    $app->post('/{id}/edit', '\App\Controllers\CommentController:update');
})->add('mw.auth');

$app->post('/api/test-post', '\App\Controllers\IndexController:testPost');

$app->group('/api/qxw', function () use ($app) {
    $app->post('/links', '\App\Controllers\QxwController:storeLink');
    $app->put('/links/{id}', '\App\Controllers\QxwController:updateLink');
    $app->post('/links/{id}/edit', '\App\Controllers\QxwController:updateLink');

    $app->post('/passwords', '\App\Controllers\QxwController:storePassword');
})->add('mw.auth');
