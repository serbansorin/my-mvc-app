<?php

require_once __DIR__ . '/vendor/autoload.php';
$commandsLoader = new \Main\Loaders\CommandsLoader();

if ($argv[1] === 'start') {
    require __DIR__ . '/boot/server.php';
} elseif ($argv[1] === 'run:dev') {
    putenv('APP_ENV=development');
    require __DIR__ . '/boot/server.php';
} else {
    $commandsLoader->run($argv);
}

function startApp()
{
    $app = Kernel\Application::getInstance();

    // Load services
    $app->loadServices([
        'router' => Router::class,
        'routeProcessor' => Main\Http\RouteProcessor::class,
        // Add other services here
    ]);

    // Process routes
    $app->processRoutes();

    // Handle the request
    $app->handleRequest();
}

// $otherCommands = [
//     'help' => 'Show the available commands',
//     'list' => 'List all the available commands',
// ];

// // if arg is start
// if ($argv[1] !== 'start') {
//     $command = $argv[1];
//     $type = $argv[2];
//     $name = $argv[3] ?? null;

// }