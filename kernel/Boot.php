<?php
namespace Kernel;

use Main\Application;
use Main\Route;

class Boot
{
    private static $instance = null;
    private $config;

    private function __construct()
    {
        $this->config = new \Config(CONFIG_DIR);
        $this->config->loadConfigFiles();
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Boot();
        }
        return self::$instance;
    }

    public static function init()
    {
        require_once ROOT_DIR . '/vendor/autoload.php';
        require_once ROOT_DIR . '/kernel/helpers.php';
    }

    public function boot()
    {
        $routeProcessor = (new Route(ROOT_DIR . '/routes/web.php'))->getRoutes();
        $route = Route::getInstance();
        // Initialize the application instance
        $app = Application::getInstance();
        
        return [$route, $app];
    }

    public function loadServices($array)
    {
        $app = Application::getInstance();
        $app->loadServices($array);
    }

    public function getConfig()
    {
        return $this->config->getAllConfig();
    }
}