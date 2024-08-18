<?php
use Monolog\Logger;

if (!function_exists('dd')) {
    function dd($data)
    {
        var_dump($data);
        die();
    }
}

if (!function_exists('log')) {
    function log($data)
    {
        $logger = new Logger('main');
        $logger->pushHandler(new \Monolog\Handler\StreamHandler('logs/app.log', Logger::DEBUG));
        $logger->debug($data);
    }
}

if (!function_exists('config')) {
    function config() {
        return \Kernel\Config::getInstance();
    }
}