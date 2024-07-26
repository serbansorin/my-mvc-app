<?php

require_once __DIR__ . '/vendor/autoload.php';

use Swoole\Http\Request;
use Swoole\Http\Response;
use Main\Route;


const ROOT_DIR = __DIR__;
const APP_DIR = ROOT_DIR . '/app';
const CONFIG_DIR = ROOT_DIR . '/config';
const PUBLIC_DIR = ROOT_DIR . '/public';
const RESOURCES_DIR = ROOT_DIR . '/resources';

// Initialize the application instance
$app = \Main\Application::getInstance();

require_once
    // Create a new Swoole HTTP server
    $http = new Swoole\Http\Server('0.0.0.0', 8000);

// Handle incoming requests
$http->on('request', function (Request $request, Response $response) use ($app) {
    // Create a new instance of the Router
    $router = Route::getInstance();
    $app->set('request', $request);
    $app->set('response', $response);

    // Handle the request
    $router->handleRequest($app, $request, $response);
});

// Set the document root
$http->set([
    'document_root' => PUBLIC_DIR,
    'enable_static_handler' => true,
]);

// Start the server
$http->start();