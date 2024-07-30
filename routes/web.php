<?php

use App\Controllers\IndexController;

$routeProcessor = new \Main\Engine\RouteProcessor(__DIR__ . '/../routes/web.php');

return [
    // this will add these middleware to all routes
    '@{people, containers, items}' => [
            'auth' => 'AuthMiddleware',
            'admin' => 'AdminMiddleware',
    ],
    '/' => ['GET', [IndexController::class, 'index']],

    // group of routes
    'people' => [
        // adding middleware with 
        '{id}' => ['GET', [IndexController::class, 'show']],
    ]
    
];