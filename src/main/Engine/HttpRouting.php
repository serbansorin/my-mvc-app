<?php

namespace Main\Engine;

use Main\Route;

/**
 * Class HttpRouting
 * 
 * This class represents the HTTP routing functionality of the application.
 */
class HttpRouting
{

    const METHODS = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'ANY'];

    /**
     * Magic method to handle dynamic method calls for adding routes.
     *
     * @param string $name The name of the method being called.
     * @param array $arguments The arguments passed to the method.
     * @throws \BadMethodCallException If the method does not exist.
     * @return void
     */
    public function __call($name, $arguments)
    {
        if (static::processKnownMethods($name, $arguments)) {
            return static::getInstance();
        }
    }

    /**
     * Magic method to handle dynamic static method calls for adding routes.
     *
     * @param string $name The name of the static method being called.
     * @param array $arguments The arguments passed to the method.
     * @throws \BadMethodCallException If the static method does not exist.
     * @return void
     */
    public static function __callStatic($name, $arguments)
    {
        if (static::processKnownMethods($name, $arguments)) {
            return static::getInstance();
        }
    }

    private static function processKnownMethods($name, $arguments): bool
    {
        if (in_array(strtoupper($name), self::METHODS)) {
            array_unshift($arguments, strtoupper($name));
            call_user_func_array([static::getInstance(), 'addRoute'], $arguments);
        } else {
            throw new \BadMethodCallException("Method $name does not exist");
        }

        return true;
    }

    /**
     * Get the instance of the HttpRouting class.
     *
     * @return HttpRouting The HttpRouting instance.
     */
    public static function getInstance()
    {
        return new self();
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
        Route::$routes[$path] = [
            'method' => $method,
            'controller' => $controller,
            'action' => $action
        ];
    }

    protected function getRoute($path)
    {
        return Route::$routes[$path];
    }

    /**
     * Get the registered routes.
     *
     * @return array The registered routes.
     */
    protected function getRoutes()
    {
        return Route::$routes;
    }
}