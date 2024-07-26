<?php

namespace Main;

use Swoole\Http\Request;
use Swoole\Http\Response;
use Main\HttpRouting;

/**
 * Route class used to handle the request and route it to the appropriate controller
 * 
 * @package Src
 */
class Route extends HttpRouting
{
    private HttpRouting $httpRouting;

    private static $instance;
    public static $routes = [];

    public function __construct($routes = [])
    {
        // i want $this to get the value of self::instance
        if (!empty($routes)) {
            $this->processRoutes($routes);
        } else {
            self::$routes = include __DIR__ . '/../config/routes.php';
            $methods = ['get', 'post', 'put', 'delete', 'patch', 'options', 'any'];

            $httpRouting = HttpRouting::getInstance();
            $this->httpRouting = $httpRouting->getRoutes();
        }
    }

    public function __call($name, $arguments)
    {
        if (in_array(strtoupper($name), ['GET', 'POST', 'PUT', 'DELETE'])) {
            array_unshift($arguments, strtoupper($name));
            call_user_func_array([$this, 'addRoute'], $arguments);
        } else {
            throw new \BadMethodCallException("Method $name does not exist");
        }
    }

    public static function __callStatic($name, $arguments)
    {
        if (in_array(strtoupper($name), ['GET', 'POST', 'PUT', 'DELETE'])) {
            array_unshift($arguments, strtoupper($name));
            call_user_func_array([new self(new HttpRouting()), 'addRoute'], $arguments);
        } else {
            throw new \BadMethodCallException("Static method $name does not exist");
        }
    }

    public function addRoute($method, $path, $controller, $action)
    {
        $this->httpRouting->addRoute($method, $path, $controller, $action);
    }

    public function getRoutes()
    {
        return $this->httpRouting->getRoutes();
    }

    public function bindNewRoutes($newRoutes)
    {
        $this->processRoutes($newRoutes);
    }

    private function processRoutes($routes = [])
    {
        // Define your routes here
        // Route::get('user', 'UserController@index')->name('user');
        // or
        // '/path' => ['method','Controller@action', 'middleware(optional)']
        // transforms into
        // '/path' => [ 'method' => 'GET', 'controller' => 'Controller', 'action' => 'method', 'middleware' => 'auth' ]
        $methods = ['get', 'post', 'put', 'delete', 'patch', 'options', 'any'];

        foreach ($routes as $path => $route) {
            $middleware = $route['middleware'] ?? $route[2] ?? null;
            $method = strtolower($route['method'] ?? $route[0]) ?? 'get';
            $controller = $route['controller'] ?? explode('@', $route[1])[0];
            $action = $route['action'] ?? explode('@', $route[1])[1] ?? 'index';

            if (in_array($method, $methods)) {
                $this->{$method}($path, $controller, $action);
                continue;
            } else {
                throw new \BadMethodCallException("Method {$method} does not exist.");
            }
        }
    }

    /*

    public static function __callStatic($name, $arguments): ?Route
    {
        $instance = Route::getInstance();
        $methods = ['get', 'post', 'put', 'delete', 'patch', 'options', 'any'];

        if (in_array($name, $methods)) {
            $path = $arguments[0];
            $controllerAction = explode('@', $arguments[1]);
            $controller = $controllerAction[0];
            $action = $controllerAction[1] ?? 'index';

            return $instance->{$name}($path, $controller, $action);
        }

        throw new \BadMethodCallException("Method {$name} does not exist.");
    }
    */


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
    public static function handleRequest(Application $app, Request $request, Response $response)
    {
        if (!self::$instance) {
            self::$instance = new Route();
        }

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