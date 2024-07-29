<?php

namespace Main;

use Swoole\Http\Request;
use Swoole\Http\Response;
use Main\Engine\RequestPathProcessor;
use Main\Engine\RouteProcessor;
use Main\Accessors\RouteGroup;

/**
 * Route class used to handle the request and route it to the appropriate controller
 * 
 * @package Src
 */
class Route extends RequestPathProcessor
{
    public RouteGroup $group;
    public RouteProcessor $process;

    private static $instance;
    public static $routes = [];

    public function __construct($routes = null)
    {
        if ($routes)
            RouteProcessor::newRoutes($routes);
    }

    public function bindNewRoutes($newRoutes): Route
    {
        $this->routes = array_merge($this->routes, $newRoutes);
        return $this;
    }


    /**
     * Add a route to the routing table.
     *
     * @param string $method The HTTP method for the route.
     * @param string $path The URL path for the route.
     * @param string $controller The controller class for the route.
     * @param string $action The action method for the route.
     * @return void
     */
    public function addRoute($method, $path, $controller, $action)
    {
        self::$routes[$path] = [
            'method' => $method,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public static function getInstance(): Route
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Group routes
     *
     * @param string $prefix
     * @return void
     */
    public function group(string $prefix, array|RouteGroup $callback)
    {
        $group = new RouteGroup($prefix, $callback);

        foreach ($group->getRoutes() as $route) {
            Route::$routes[$route['path']] = [
                'method' => $route['method'],
                'controller' => $route['controller'],
                'action' => $route['action']
            ];
        }
    }


    public function __destruct()
    {
        // Save the instance to a static variable
        self::$instance = $this;
    }
}