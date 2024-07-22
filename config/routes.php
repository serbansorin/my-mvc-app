<?php

use App\Controllers\IndexController;

return [
    '/' => ['GET', IndexController::class, 'index'],
];