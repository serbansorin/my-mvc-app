<?php

namespace Main\Accessors;

use \Main\Engine\RouterMethods;
use \Main\Router;

class RouteGroup
{
    private $prefix;
    private RouterMethods $rtProc;
    private $routes = [];

    public function __construct($prefix, $callback)
    {
        $this->prefix = $prefix;
        $this->rtProc = new RouterMethods();
        $this->processGroup($callback);
    }

    private function processGroup($group)
    {
        $middleware = $group['@mid'] ?? null;
        unset($group['@mid']);

        foreach ($group as $path => $route) {
            $method = $route[0];
            $controllerAction = $route[1];

            if (is_string($controllerAction) && strpos($controllerAction, '@') === false) {
                $controllerAction .= '@index';
            }
            if (is_string($controllerAction) && strpos($controllerAction, '@') !== false) {
                $controllerAction = explode('@', $controllerAction);
            }

            $routeMiddleware = implode('|', $middleware) . '|' . $route['@mid'] ?? null;
            $this->addRoute($method, $path, $controllerAction[0], $controllerAction[1], $routeMiddleware);
        }
    }

    public function addRoute($method, $path, $controller, $action, $middleware = null)
    {
        $fullPath = $this->prefix . $path;
        $this->routes[$fullPath] = [
            'method' => strtoupper($method),
            'controller' => $controller,
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function addPrefixToRoute($routes)
    {
        $prefix = $this->prefix;
        $prefixedRoutes = [];
        foreach ($routes as $path => $route) {
            $prefixedRoutes[$prefix . $path] = $route;
        }
        return Router::getInstance()->bindNewRoutes($prefixedRoutes);
    }
}