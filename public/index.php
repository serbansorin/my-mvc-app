<?php

// Autoload dependencies
require_once ROOT_DIR . '/vendor/autoload.php';

// Load the bootstrap file
require_once ROOT_DIR . '/kernel/bootstrap.php';

// Initialize the application instance
$app = \Main\Application::getInstance();

// Handle the request
$router = \Main\Route::getInstance();
$router->handleRequest($app, $app->get('request'), $app->get('response'));

