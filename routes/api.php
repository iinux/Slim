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
