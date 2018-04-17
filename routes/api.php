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

$app->group('/api/common', function () use ($app) {
})->add('mw.secret-key-auth');

$app->group('/api/science', function () use ($app) {
    $app->post('/alpha', '\App\Controllers\Controller:alpha');
    $app->post('/beta', '\App\Controllers\Controller:beta');
})->add('mw.secret-key-auth');

$app->post('/api/test-post', '\App\Controllers\IndexController:testPost');

$app->group('/api/qxw', function () use ($app) {
    $app->post('/links', '\App\Controllers\QxwController:storeLink');
    $app->put('/links/{id}', '\App\Controllers\QxwController:updateLink');
    $app->post('/links/{id}/edit', '\App\Controllers\QxwController:updateLink');

    $app->post('/passwords', '\App\Controllers\QxwController:storePassword');
})->add('mw.auth');

$app->group('/api/users', function () use ($app) {
    $app->post('', '\App\Controllers\UserController:store');
    $app->get('', '\App\Controllers\UserController:index');
    $app->put('/{id}', '\App\Controllers\UserController:update');
    $app->post('/{id}/edit', '\App\Controllers\UserController:update');
    $app->delete('/{id}', '\App\Controllers\UserController:destroy');
    $app->post('/{id}/delete', '\App\Controllers\UserController:destroy');
})->add('mw.root-auth');
