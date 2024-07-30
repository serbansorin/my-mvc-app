<?php
require_once __DIR__ . '/vendor/autoload.php';

// Load the kernel file
[$route, $app] = require_once ROOT_DIR . '/boot/kernel.php';

use Swoole\Http\Request;
use Swoole\Http\Response;

// Create a new Swoole HTTP server
$http = new Swoole\Http\Server('0.0.0.0', 8000);

// Handle incoming requests
$http->on('request', function (Request $request, Response $response) use ($app) {
    // Set request and response in the app instance
    $app->set('request', $request);
    $app->set('response', $response);

    // Handle the request
    ob_start();
    include PUBLIC_DIR . '/index.php';
    $content = ob_get_clean();

    // Send the response
    $response->end($content);
});

// Set the document root
$http->set([
    'document_root' => PUBLIC_DIR,
    'enable_static_handler' => true,
]);

// Start the server
$http->start();