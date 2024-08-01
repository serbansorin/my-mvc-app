<?php
namespace Kernel;

use Main\Engine\RouteProcessor;
use Main\Router;

class Bootstrap
{
    private static $instance = null;
    public static Config $config;
    public static Application $app;
    public static Router $router;
    public static RouteProcessor $routeProcessor;

    public static array $services = [
        'config' => Config::class,
        'app' => Application::class,
        'router' => Router::class,
        'routeProcessor' => RouteProcessor::class
    ];


    public function __construct()
    {
        if (self::$instance !== null) {
            return;
        }

        self::$app = Application::getInstance();
        self::$config = new Config(CONFIG_DIR);
        self::$config->loadConfigFiles();

        self::$app->set('config', $this->config);

        self::$instance = $this;
    }

    public static function init()
    {
        return self::$instance ?? new self();
    }

    public static function reload()
    {
        self::$instance = null;
        return new self();
    }

    public function services($array)
    {
        foreach ($array as $key => $value) {
            if (array_key_exists($key, self::$services)) {
                self::$app->set($key, new $value());
            }
        }
    }

    public function getConfig()
    {
        return $this->config->getAllConfig();
    }

    private function isStaticAndExists($name)
    {
        return property_exists(self::class, $name) && is_object(self::$name);
    }

    public function __get($name)
    {
        if ($this->isStaticAndExists($name)) {
            return self::$name;
        }

        return null;
    }
}