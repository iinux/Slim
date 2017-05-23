<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/5/21
 * Time: 23:11
 */

$app->post('/api/comments', '\App\Controllers\CommentController:store');

$app->post('/api/test-post', '\App\Controllers\IndexController:testPost');
