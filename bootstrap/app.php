<?php

use Slim\Factory\AppFactory;
use App\Http\Middleware\Authenticate;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

require_once __DIR__.'/../support/helpers.php';
require __DIR__.'/../vendor/autoload.php';

$illuminateApp = new Illuminate\Foundation\Application(
    realpath(__DIR__ . '/../')
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
    $request = Illuminate\Http\Request::capture();
    $illuminateApp->instance('request', $request);
    $kernel->bootstrap();
}

AppFactory::setContainer($illuminateApp);
$app = AppFactory::create();

$config = require __DIR__.'/../config/slim_config.php';
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$app->add(function (Request $request, RequestHandler $handler) use($illuminateApp, $app) {
    $illuminateApp['slim_request'] = $request;
    $illuminateApp['slim_app'] = $app;
    $response = $handler->handle($request);
//    $response->getBody()->write('World');

    return $response;
});

session_save_path(__DIR__ . '/../storage/session');
ini_set('session.cache_expire', 0);
ini_set('session.cookie_lifetime', 0);
session_start();

$container = $app->getContainer();

require_once __DIR__.'/../routes/web.php';
require_once __DIR__.'/../routes/api.php';

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

$app->run();
