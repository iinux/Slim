<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2016/8/21
 * Time: 10:00
 */
require '../vendor/autoload.php';

$app = new Slim\App();

$app->get('/hello/{name}', function ($request, $response, $args) {
    $response->write("Hello, " . $args['name']);
    return $response;
});

$app->get('/', '\App\Controllers\IndexController:index');

$config = [
    'displayErrorDetails' => true,
];
$app->addSettings($config);

$app->run();
