<?php

require_once __DIR__ . '/vendor/autoload.php';

use Swoole\Http\Request;
use Swoole\Http\Response;
use Src\Route;

// Create a new Swoole HTTP server
$http = new Swoole\Http\Server('0.0.0.0', 8000);

// Handle incoming requests
$http->on('request', function (Request $request, Response $response) {
    // Create a new instance of the Router
    $router = Route::getInstance();

    // Handle the request
    $router->handleRequest($request, $response);
});

// Start the server
$http->start();
