<?php

use App\Controllers\IndexController;

$routeProcessor = new \Main\Engine\RouteProcessor(ROOT_DIR . '/routes/web.php');

return [
    '/' => ['GET', IndexController::class, 'index'],
];