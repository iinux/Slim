<?php

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
        'auth'            => \App\Http\Middleware\Authenticate::class,
        'root-auth'       => \App\Http\Middleware\RootAuthenticate::class,
        'secret-key-auth' => \App\Http\Middleware\SecretKeyAuthenticate::class,
    ],
];