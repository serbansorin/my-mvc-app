<?php

require_once __DIR__ . '/_loaded/constants.php';

$bootstrapLoaded ??= false;

if ($bootstrapLoaded) {
    return [$route, $app, $bootstrap];
}

$bootstrap = \Kernel\Bootstrap::init();
$app = $bootstrap->$app;

$services = [
    // 'config' => \Kernel\Config::class,
    // 'app' => \Kernel\Application::class,
    'router' => $route,
    'routeProcessor' => \Main\Http\RouteProcessor::class
];
/*   Loading services */

$app->loadServices([
    'router' => $route,
]);

$bootstrapLoaded = true;

return [$route, $app];




// Bootstrap::init();

// $bootstrap = Bootstrap::getInstance();
// list($route, $app) = $bootstrap->boot();

// $app->loadServices([
//     'router' => $route,
// ]);

// return [$route, $app];