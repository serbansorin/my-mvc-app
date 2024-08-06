<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/_loaded/constants.php';

$commandsLoader = new \Main\Loaders\CommandsLoader();

if ($argv[1] === 'start') {
    require __DIR__ . '/boot/server.php';

} elseif ($argv[1] === 'run:dev') {
    putenv('APP_ENV=development');
    require __DIR__ . '/boot/server.php';
    
} else {
    $commandsLoader->run($argv);
}
