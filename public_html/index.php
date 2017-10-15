<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2016/8/21
 * Time: 10:00
 */
require __DIR__.'/../vendor/autoload.php';

$app = new Slim\App();

require_once __DIR__.'/../support/helpers.php';

$config = require_once __DIR__.'/../config/slim_config.php';
$app->addSettings($config);

session_start();

$container = $app->getContainer();
foreach ($config['routeMiddleware'] as $key => $value) {
    $container["mw.$key"] = function () use ($value) {
        return new $value();
    };
}

require_once __DIR__.'/../routes/web.php';
require_once __DIR__.'/../routes/api.php';

// date_default_timezone_set('Asia/Shanghai');

require_once __DIR__.'/../bootstrap/app.php';

$app->run();
