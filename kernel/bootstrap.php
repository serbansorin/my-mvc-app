<?php

require_once __DIR__ . '/_loaded/constants.php';


$bootstrap = \Kernel\Bootstrap::init();
$app = $bootstrap->$app;

$services = 
/*   Loading services */

$app->loadServices([
    'router' => $route,
]);

