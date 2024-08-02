<?php

namespace Kernel;

use Main\Router;

class Application
{
    use \Singleton;

    public static $container = [];
    public static $singleton = [];
    public static $config;

    private function __construct()
    {
        // Load configuration
        Bootstrap::init();
        self::$config = new Config(CONFIG_DIR);
        self::$config->loadConfigFiles();
        $this->set('config', self::$config);
    }

    public static function storeInContainer($key, $value)
    {
        self::$instance->container[strtolower($key)] = $value;
    }

    public function set($key, $value)
    {
        self::$instance->container[strtolower($key)] = $value;
    }

    public function get($key)
    {
        return self::$instance->container[strtolower($key)] ?? null;
    }

    public function has($key)
    {
        return isset($this->container[strtolower($key)]);
    }

    public function remove($key)
    {
        unset($this->container[strtolower($key)]);
    }

    public function loadServices()
    {
        Services::register();
    }

    public function processRoutes()
    {
        require_once CONFIG_DIR . '/routes.php';
        require_once ROOT_DIR . '/routes/web.php';
    }

    public function handleRequest()
    {
        $path = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        $route = Router::match($method, $path);

        if ($route) {
            $controllerAction = explode('@', $route['action']);
            $controller = new $controllerAction[0]();
            $action = $controllerAction[1];

            return $controller->$action();
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}