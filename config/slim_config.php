<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/5/21
 * Time: 22:15
 */

return [
    'displayErrorDetails'               => true,
    //'routerCacheFile'                   => __DIR__ . '/../bootstrap/cache/routes.php',
    'routerCacheFile'                   => false,
    'httpVersion'                       => '1.1',
    'responseChunkSize'                 => 4096,
    'outputBuffering'                   => 'append',
    'determineRouteBeforeAppMiddleware' => false,
    'addContentLengthHeader'            => true,
    'routeMiddleware'                   => [
        'auth' => \App\Http\Middleware\Authenticate::class,
    ]
];