<?php

use Kernel\Config;


enum Providers
{
    case Logger = 'logger';
    case ErrorHandler = 'errorHandler';
    case CSRF = 'csrf';
    case Session = 'session';
    case DS = 'ds';
    case Collection = 'collection';
    case Struct = 'struct';
    case Config = 'config';
}


function getEnumCaseName(Providers $provider): string
{
    $caseEnumReflection = new \ReflectionClass($provider);
    return $caseEnumReflection->getShortName();
}


$serviceProviders = [
    'config' => Config::getInstance(),
    // 'logger' => \Main\Providers\Logger::class,
    // 'errorHandler' => \Main\Providers\ErrorHandler::class,
    // 'csrf' => \Main\Providers\CSRFServiceProvider::class,
    // 'session' => \Main\Providers\Session::class,
    'ds' => DS::class,
    // 'collection' => \Main\Providers\Collection::class,
    // 'struct' => \Main\Providers\Struct::class
];