<?php

namespace Main\Accessors;

use \Main\Engine\HttpMethodProcessor;

class RouteGroup
{
    private $prefix;
    private HttpMethodProcessor $rtProc;
    private $routes = [];

    public function __construct($prefix, $callback)
    {
        $this->prefix = $prefix;
    }

    public function addRoute($method, $path, $controller, $action)
    {
        $fullPath = $this->prefix . $path;
        $this->rtProc->addRoute($method, $fullPath, $controller, $action);
    }

    public function getRoutes()
    {
        return $this->rtProc->getRoutes();
    }

    private function addPrefixToRoute($routes)
    {
        $prefix = $this->prefix;
        $prefixedRoutes = [];

        foreach ($routes as $path => $route) {
            $prefixedRoutes[$prefix . $path] = $route;
        }

        return \Main\Router::getInstance()->bindNewRoutes($prefixedRoutes);
    }
}