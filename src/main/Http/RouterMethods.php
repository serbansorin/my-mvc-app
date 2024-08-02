<?php

namespace Main\Engine;

use Main\Router;

/**
 * Class RouterMethods
 * 
 * This class represents the HTTP routing functionality of the application.
 */
class RouterMethods
{

    const HTTP_METHODS = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'ANY'];

    /**
     * Get the HTTP methods that the router supports.
     *
     * @return array The supported HTTP methods.
     */
    public function getHttpMethods(): array
    {
        return self::HTTP_METHODS;
    }


    /**
     * Check if the given method is a valid HTTP method.
     *
     * @param string $method The method to check.
     * @return bool True if the method is valid, false otherwise.
     */
    public function isHttpMethod($method): bool
    {
        return in_array(strtoupper($method), self::HTTP_METHODS);
    }

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
            static::getInstance();
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
            static::getInstance();
        }
    }

    private static function processKnownMethods($name, $arguments): bool
    {
        if (in_array(strtoupper($name), self::HTTP_METHODS)) {
            array_unshift($arguments, strtoupper($name));
            call_user_func_array([static::getInstance(), 'addRoute'], $arguments);
        } else {
            throw new \BadMethodCallException("Method $name does not exist");
        }

        return true;
    }


    public function getVarsFromPath($path): array
    {
        $path = explode('/', $path);
        $vars = [];
        foreach ($path as $key => $value) {
            if ($key % 2 == 0) {
                $vars[$value] = $path[$key + 1];
            }
        }
        return $vars;
    }

    protected function getRoute($path)
    {
        return Router::$routes[$path];
    }

    /**
     * Get the registered routes.
     *
     * @return array The registered routes.
     */
    protected function getRoutes()
    {
        return Router::$routes;
    }
}