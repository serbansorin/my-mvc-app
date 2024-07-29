<?php

namespace Main\Handlers;

use Main\Application;
use Main\Config;
use Main\Route;
use Primary\Facades\RequestFacade;

class HandleRequests
{
    private $routeProcessor;
    private $config;

    public function __construct()
    {
        $this->config = new Config();
        $this->routeProcessor = new RouteProcessor($this->config->get('routes'));
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
            RequestFacade::setFacadeApplication($app);
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
}