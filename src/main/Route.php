<?php

namespace Main;

use Swoole\Http\Request;
use Swoole\Http\Response;
use Main\Engine\HttpRouting;
use Main\Engine\RouteProcessor;
use Main\Accessors\RouteGroup;

/**
 * Route class used to handle the request and route it to the appropriate controller
 * 
 * @package Src
 */
class Route extends HttpRouting
{
    public \Main\Accessors\RouteGroup $group;
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

    public static function getInstance(): Route
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

        self::handleRequest($app, $request, $response);
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