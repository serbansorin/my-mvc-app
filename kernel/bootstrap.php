<?php

use Main\Application;
use App\Controllers\IndexController;

// Initialize the application instance
$app = Application::getInstance();

// Register services

$app->set('view', Main\View::initialize(__DIR__ . '../resources/views'));

// Add other services as needed
// $app->set('serviceName', new ServiceClass());