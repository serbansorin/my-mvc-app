<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../boot/kernel.php';




// Handle the request
$router = \Main\Route::getInstance();
$router->handleRequest($app, $app->get('request'), $app->get('response'));

