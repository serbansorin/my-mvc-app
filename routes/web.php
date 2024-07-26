<?php

use App\Controllers\IndexController;

$routeProcessor = new \Main\Engine\RouteProcessor(__DIR__ . '/../routes/web.php');

return [
    '/' => ['GET', IndexController::class, 'index'],
];