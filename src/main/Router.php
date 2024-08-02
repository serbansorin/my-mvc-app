<?php

namespace Main;

use Closure;

use Swoole\Http\Request;
use Swoole\Http\Response;
use Main\Accessors\RouteGroup;

use Main\Engine\RouteProcessor;
use Main\Engine\HttpMethodProcessor;

/**
 * Router class used to handle the request and route it to the appropriate controller
 * 
 * @package Src
 */
class Router
{
    use \Singleton;

    public RouteGroup $group;
    public RouteProcessor $process;

    private static HttpMethodProcessor $httpProc;
    public static $routes = [];

    public function __construct($routes = [])
    {
        if ($routes)
            RouteProcessor::newRoutes($routes);
        self::$httpProc = new HttpMethodProcessor();
    }

    public function bindNewRoutes($newRoutes): Router
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

    /**
     * Group routes
     *
     * @param string $prefix
     * @return void
     */
    public function group(string $prefix, Closure $callback)
    {
        if (is_array($callback)) {
            $this->group = new RouteGroup($prefix, $callback);
        } else {
            $this->group = new RouteGroup($prefix, $callback);
            $callback($this->group);
            $this->group->addPrefixToRoute($this->group->getRoutes());
        }

    }

    static function handleRequest(Request $request, Response $response)
    {
        return self::$httpProc->handleRequest($request, $response);
    }

    /**
     * Get array from string ex: "Controller@action"
     * @param string $str
     */
    static function parseMethodAndAction(string|array $controllerAction): array
    {
        if (is_string($controllerAction)) {

            $controllerAction = explode('@', $controllerAction);
        }
        $controller = $controllerAction[0];
        $action = $controllerAction[1] ?? 'index';

        return [
            'method' => $controller,
            'action' => $action
        ];
    }

    public function __call($method, $args)
    {
        return match ($method) {
            'get', 'post', 'put', 'delete' => $this->addRoute(strtoupper($method), ...$args),
            default => throw new \BadMethodCallException("Method $method does not exist")
        };
    }

    public static function __callStatic($method, $args)
    {
        $instance = self::getInstance();

        return match ($method) {
            'get', 'post', 'put', 'delete' => $instance->addRoute(strtoupper($method), ...$args),
            'addRoute' => $instance->addRoute(...$args),
            default => throw new \BadMethodCallException("Method $method does not exist")
        };
    }
}