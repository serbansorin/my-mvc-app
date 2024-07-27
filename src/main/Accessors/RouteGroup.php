<?php

namespace Main\Accessors;

use \Main\Engine\HttpRouting;

class RouteGroup
{
    private $prefix;
    private HttpRouting $httpRouting;
    private \Main\Route $route;

    public function __construct($prefix, $callback)
    {
        $this->prefix = $prefix;
        $this->httpRouting = HttpRouting::getInstance();
    }

    public function addRoute($method, $path, $controller, $action)
    {
        $fullPath = $this->prefix . $path;
        $this->httpRouting->addRoute($method, $fullPath, $controller, $action);
    }

    public function getRoutes()
    {
        return $this->httpRouting->getRoutes();
    }

    private function addPrefixToRoute($routes)
    {
        $prefix = $this->prefix;
        $prefixedRoutes = [];

        foreach ($routes as $path => $route) {
            $prefixedRoutes[$prefix . $path] = $route;
        }

        return \Main\Route::getInstance()->bindNewRoutes($prefixedRoutes);
    }
}