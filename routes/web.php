<?php

use App\Controllers\IndexController;
use Main\Router;

Router::get('/home', 'App\Controllers\HomeController@index');
Router::get('/about', 'App\Controllers\AboutController@index');

// or

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