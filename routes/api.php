<?php
/**
 * User: nalux
 * Date: 2017/5/21
 * Time: 23:11
 */

use Slim\Routing\RouteCollectorProxy;
use App\Http\Middleware\Authenticate;

$app->group('/api/comments', function (RouteCollectorProxy $group) use ($app) {
    $group->post('', '\App\Controllers\CommentController:store');
    $group->put('/{id}', '\App\Controllers\CommentController:update');
    $group->post('/{id}/edit', '\App\Controllers\CommentController:update');
})->add(new Authenticate());

$app->group('/api/common', function (RouteCollectorProxy $group) use ($app) {
})->add('mw.secret-key-auth');

$app->group('/api/science', function (RouteCollectorProxy $group) use ($app) {
    $group->post('/alpha', '\App\Controllers\Controller:alpha');
    $group->post('/beta', '\App\Controllers\Controller:beta');
})->add('mw.secret-key-auth');

$app->post('/api/test-post', '\App\Controllers\IndexController:testPost');
$app->post('/api/wedding-user', '\App\Controllers\WeddingController:userStore');
$app->get('/api/switch/{id}/{content}', '\App\Controllers\SwitchController:update');

$app->group('/api/qxw', function (RouteCollectorProxy $group) use ($app) {
    $group->post('/links', '\App\Controllers\QxwController:storeLink');
    $group->put('/links/{id}', '\App\Controllers\QxwController:updateLink');
    $group->get('/links/{id}/content', '\App\Controllers\QxwController:getLinkContent');
    $group->post('/links/{id}/edit', '\App\Controllers\QxwController:updateLink');

    $group->post('/passwords', '\App\Controllers\QxwController:storePassword');
})->add(new Authenticate());

$app->group('/api/users', function (RouteCollectorProxy $group) use ($app) {
    $group->post('', '\App\Controllers\UserController:store');
    $group->get('', '\App\Controllers\UserController:index');
    $group->put('/{id}', '\App\Controllers\UserController:update');
    $group->post('/{id}/edit', '\App\Controllers\UserController:update');
    $group->delete('/{id}', '\App\Controllers\UserController:destroy');
    $group->post('/{id}/delete', '\App\Controllers\UserController:destroy');
})->add('mw.root-auth');
