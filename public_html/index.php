<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2016/8/21
 * Time: 10:00
 */
require '../vendor/autoload.php';

$app = new Slim\App();

require_once '../support/helpers.php';

$config = require_once '../config/slim_config.php';
$app->addSettings($config);

session_start();

$container = $app->getContainer();
foreach ($config['routeMiddleware'] as $key => $value) {
    $container["mw.$key"] = function () use ($value) {
        return new $value();
    };
}

require_once '../routes/web.php';
require_once '../routes/api.php';

// date_default_timezone_set('Asia/Shanghai');

require_once '../bootstrap/app.php';

$app->run();
