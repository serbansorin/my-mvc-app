<?php

return [
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],
    'web' => [
        'middlewareFiles' => [
            ROOT_DIR . 'middleware/AuthMiddleware.php',
        ],
        'routesFiles' => [
            ROOT_DIR . 'routes/web.php',
        ],
        'main' => ROOT_DIR . 'routes/web.php',
    ],
];