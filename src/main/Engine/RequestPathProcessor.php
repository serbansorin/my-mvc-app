<?php

namespace Main\Engine;

use Main\Route;

/**
 * Class RequestPathProcessor
 * 
 * This class represents the HTTP routing functionality of the application.
 */
class RequestPathProcessor
{

    const HTTP_METHODS = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'ANY'];

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

    /**
     * Get the instance of the RequestPathProcessor class.
     *
     * @return RequestPathProcessor The RequestPathProcessor instance.
     */
    public static function getInstance()
    {
        return new static;
    }


    /**
     * Get array from string ex: "Controller@action"
     * @param string $str
     */
    public function getMethodActionFromString(string $str): array
    {
        $controllerAction = explode('@', $str);
        $controller = $controllerAction[0];
        $action = $controllerAction[1] ?? 'index';

        return [
            'method' => $controller,
            'action' => $action
        ];
    }

    public function getVarsFromPath($path)
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