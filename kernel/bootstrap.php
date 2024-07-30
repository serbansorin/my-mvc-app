<?php

$bootstrapLoaded = false;

if ($bootstrapLoaded) {
    return [$route, $app, $bootstrap];
}

use Kernel\Boot as Bootstrap;

const ROOT_DIR = dirname(__DIR__);
const APP_DIR = ROOT_DIR . '/app';
const CONFIG_DIR = ROOT_DIR . '/config';
const PUBLIC_DIR = ROOT_DIR . '/public';
const RESOURCES_DIR = ROOT_DIR . '/resources';

Bootstrap::init();

$bootstrap = Bootstrap::getInstance();
list($route, $app) = $bootstrap->boot();

$app->loadServices([
    'router' => $route,
]);

$bootstrapLoaded = true;
return [$route, $app];