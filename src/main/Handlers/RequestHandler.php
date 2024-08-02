<?php

namespace Main\Handlers;

use Main\Router;
use Kernel\Config;
use Kernel\Application;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Main\Engine\RouteProcessor;

/**
 * RequestHandler class used to handle the request and route it to the appropriate controller
 * 
 * @package Main
 */
class RequestHandler
{
    use \Singleton;

    private RouteProcessor $routeProcessor;
    private $config;
    private Router $router;

    private function __construct()
    {
        $this->config = new Config();
        $this->routeProcessor = new RouteProcessor(Router::$routes);
    }


    /**
     * Handle the request
     *
     * @param Request $request
     * @param Response $response
     */
    public static function handleRequest(Request $request, Response $response)
    {

        $path = $request->server['request_uri'];
        $method = $request->server['request_method'];

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

        return $response;
    }

    public static function run()
    {
        $app = Application::getInstance();
        $request = $app->get('request');
        $response = $app->get('response');

        self::handleRequest( $request, $response);
    }
}