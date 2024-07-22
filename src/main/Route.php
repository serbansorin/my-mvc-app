<?php

namespace Src;

use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * Route class
 * used to handle the request and route it to the appropriate controller
 * @package Src
 */
class Route
{
    private static $instance;
    public $routes = [];

    private function __construct()
    {
        // Define your routes here
        $routes = include __DIR__ . '/../config/routes.php';
        $methods = ['get', 'post', 'put', 'delete', 'patch', 'options', 'any'];

        // Route::get('user', 'UserController@index')->name('user');
        // or
        // '/path' => ['method','Controller@action', 'middleware(optional)']
        // transforms into
        // '/path' => [ 'method' => 'GET', 'controller' => 'Controller', 'action' => 'method', 'middleware' => 'auth' ]


        foreach ($routes as $path => $route) {

            if (count($route) > 1) {
                if (isset($route['method'], $route['controller'], $route['action'])) {
                    $method = strtolower($route['method']);
                    $controller = $route['controller'];
                    $action = $route['action'];
                    $middleware = $route['middleware'] ?? null;
                } else {
                    $method = strtolower($route[0]);
                    if (str_contains($route[1], '@')) {
                        [$controller, $action] = explode('@', $route[1]);
                        $route[1] = $controller;
                        $route[2] = $action;
                    } else {
                        $controller = $route[1];
                        $action = $route[2] ?? 'index';
                    }
                }
            }

            if (in_array($method, $methods)) {
                $this->{$method}($path, $controller, $action);
            }
        }
    }

    public function get($path, $controller, $action)
    {
        $this->routes[$path] = [
            'method' => 'GET',
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function post($path, $controller, $action)
    {
        $this->routes[$path] = [
            'method' => 'POST',
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function put($path, $controller, $action)
    {
        $this->routes[$path] = [
            'method' => 'PUT',
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function delete($path, $controller, $action)
    {
        $this->routes[$path] = [
            'method' => 'DELETE',
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function patch($path, $controller, $action)
    {
        $this->routes[$path] = [
            'method' => 'PATCH',
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function options($path, $controller, $action)
    {
        $this->routes[$path] = [
            'method' => 'OPTIONS',
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function any($path, $controller, $action)
    {
        $this->routes[$path] = [
            'method' => 'ANY',
            'controller' => $controller,
            'action' => $action
        ];
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * Handle the request
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public static function handleRequest(Request $request, Response $response)
    {
        if (!self::$instance) {
            self::$instance = new Route();
        }

        $app = Application::getInstance();
        $path = $request->server['request_uri'];
        $method = $request->server['request_method'];

        $app->set('request', $request);
        $app->set('response', $response);

        if (isset(self::$instance->routes[$path])) {
            $route = self::$instance->routes[$path];

            if ($route['method'] === $method) {
                $controller = new $route['controller']();
                $controller->{$route['action']}();
            } else {
                // Handle 405 Method Not Allowed
                $response->status(405);
                $response->end("405 Method Not Allowed");
            }
        } else {
            // Handle 404 Not Found
            $response->status(404);
            $response->end("404 Not Found");
        }

        // Handle 404 Not Found
        $response->status(404);
        $response->end("404 Not Found");
    }

    public function run()
    {
        $app = Application::getInstance();
        $request = $app->get('request');
        $response = $app->get('response');

        self::handleRequest($request, $response);
    }

    public function __destruct()
    {
        // Save the instance to a static variable
        self::$instance = $this;
    }

    public function __callStatic($name, $arguments)
    {
        $methods = ['get', 'post', 'put', 'delete', 'patch', 'options', 'any'];

        // Route::get('user', 'UserController@index')->name('user');
        if (in_array($name, $methods)) {
            if (count($arguments) === 2) {
                $this->{$name}($arguments[0], $arguments[1], 'index');
            } elseif (count($arguments) === 3) {
                $this->{$name}($arguments[0], $arguments[1], $arguments[2]);
            }
        }
    }
}
