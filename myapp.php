<?php

require_once __DIR__ . '/vendor/autoload.php';


$commandsArgs = [
    'make' => [
        'controller' => 'Create a new controller',
        'model' => 'Create a new model',
        'migration' => 'Create a new migration',
        'event' => 'Create a new event',
        'listener' => 'Create a new listener',
        'middleware' => 'Create a new middleware',
        'command' => 'Create a new command',
        'job' => 'Create a new job',
    ],
    'run' => [
        'migrate' => 'Run the migrations',
        'queue' => 'Run the queue',
        'prod' => 'Run the application in production mode',
        'production' => 'Run the application in production mode',
        'dev' => 'Run the application in development mode',
        'development' => 'Run the application in development mode',
    ],
];

$otherCommands = [
    'help' => 'Show the available commands',
    'list' => 'List all the available commands',
];

// if arg is start
if ($argv[1] !== 'start') {
    $command = $argv[1];
    $type = $argv[2];
    $name = $argv[3] ?? null;

}