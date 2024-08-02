<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../kernel/bootstrap.php';


// Handle the request
$router = Main\Router::getInstance();
$router->handleRequest($app, $app->get('request'), $app->get('response'));

