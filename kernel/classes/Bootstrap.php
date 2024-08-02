<?php
namespace Kernel;

use Main\Http\RouteProcessor;
use Main\Router;

/**
 * Init Application and Config
 * then load services
 */
class Bootstrap
{
    use \Singleton;

    public static Config $config;
    public static Application $app;
    public static Services $services;


    private function __construct()
    {
        self::$app = Application::getInstance();
        self::$config = new Config(CONFIG_DIR);
        self::$config->loadConfigFiles();
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