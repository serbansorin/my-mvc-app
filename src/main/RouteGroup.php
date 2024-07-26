<?php

namespace Main;

use \Main\HttpRouting;

class RouteGroup
{
    private $prefix;
    private HttpRouting $httpRouting;
    private Route $route;

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

    // public function __call($name, $arguments)
    // {
    //     if (in_array(strtoupper($name), ['GET', 'POST', 'PUT', 'DELETE'])) {
    //         array_unshift($arguments, strtoupper($name));
    //         call_user_func_array([$this, 'addRoute'], $arguments);
    //     } else {
    //         throw new \BadMethodCallException("Method $name does not exist");
    //     }
    // }

    // public static function __callStatic($name, $arguments)
    // {
    //     if (in_array(strtoupper($name), ['GET', 'POST', 'PUT', 'DELETE'])) {
    //         array_unshift($arguments, strtoupper($name));
    //         call_user_func_array([new self('', new HttpRouting()), 'addRoute'], $arguments);
    //     } else {
    //         throw new \BadMethodCallException("Static method $name does not exist");
    //     }
    // }

    private function addPrefixToRoute($routes)
    {
        $prefix = $this->prefix;
        $prefixedRoutes = [];

        foreach ($routes as $path => $route) {
            $prefixedRoutes[$prefix . $path] = $route;
        }

        return Route::getInstance()->bindNewRoutes($prefixedRoutes);
    }
}
