<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2016/8/21
 * Time: 10:00
 */
require '../vendor/autoload.php';

$app = new Slim\App();

require_once '../routes/web.php';
require_once '../routes/api.php';

$config = require_once '../config/slim_config.php';
$app->addSettings($config);
// date_default_timezone_set('Asia/Shanghai');

/*
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'id1569105_000',
    'username'  => 'id1569105_nalux',
    'password'  => 'lanlan520',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
// Set the event dispatcher used by Eloquent models... (optional)
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

$capsule->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();
*/

$illuminateApp = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);
$illuminateApp->singleton(
    'Illuminate\Contracts\Http\Kernel',
    'App\Http\Kernel'
);
$illuminateApp->singleton(
    'Illuminate\Contracts\Debug\ExceptionHandler',
    'App\Exceptions\Handler'
);
/**
 * @var \App\Http\Kernel $kernel
 */
$kernel = $illuminateApp->make('Illuminate\Contracts\Http\Kernel');
if (false) {
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
} else {
    $kernel->bootstrap();
}

$app->run();
