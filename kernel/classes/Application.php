<?php

namespace Kernel;

use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * The Application class represents the main application container.
 */
class Application
{
    private static $instance;
    private $container = [];
    public static $singleton = [];
    private static Config $config;

    /**
     * Application constructor.
     * Initializes the container with default services.
     */
    private function __construct(...$data)
    {
        if (file_exists(CONFIG_DIR . '/services.php')) {
            $tmpContainer = require CONFIG_DIR . '/services.php';
            $this->processContainer($tmpContainer);
        }
    }

    /**
     * Processes the container by resolving callable services.
     */
    private function processContainer($container)
    {
        foreach ($container as $key => $value) {
            if (is_object($value)) {
                $this->storeInContainer($key, $value);
            } elseif (is_callable($value) && method_exists($value, '__invoke')) {
                $this->storeInContainer($key, $value($this));
            } elseif (is_string($value) && class_exists($value)) {
                $this->storeInContainer($key, new $value());
            } else {
                throw new \ErrorException("Invalid service: $value", 1);
            }
        }
    }

    /**
     * Returns the singleton instance of the Application class.
     *
     * @return Application The singleton instance.
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Retrieves a service from the container by its key.
     *
     * @param string $key The key of the service.
     * @return mixed|null The retrieved service or null if not found.
     */
    public function get($key)
    {
        return $this->container[strtolower($key)] ?? null;
    }

    /**
     * Sets a service in the container.
     */
    public function set(string $key, string|object|null $value = null)
    {
        $key = strtolower($key);

        // If the value is empty, try to instantiate a class with the same name
        if (empty($value)) {
            $tmp = ucfirst($key);
            if (class_exists("App\\$tmp")) {
                $value = "App\\$tmp";
            } elseif (class_exists("Main\\$tmp")) {
                $value = "Main\\$tmp";
            }
            // else if class extends Facades class
            else {
                // get all files in Facades directory
                $files = scandir(ROOT_DIR . '/src/globals/Facades');

                // check if file exists
                $fileN = array_filter($files, function ($file) use ($tmp) {
                    return $file === $tmp . '.php' || $file === $tmp . 'Facade.php';
                })[0] ?? null;

                if (!isset($fileN)) { // if file not found
                    throw new \ErrorException("Invalid service: $value", 1);
                } else {
                    $value = require ROOT_DIR . '/src/globals/Facades/' . $fileN;
                }

                $this->storeInContainer($key, $value);
                return;
            }
        }

        // If the value is an object, store it in the container
        if (is_object($value)) {
            $this->storeInContainer($key, $value);

            // If the value is a callable, invoke it
        } elseif (is_callable($value) && method_exists($value, '__invoke')) {
            $this->storeInContainer($key, $value($this));

            // If the value is a string and the class exists, instantiate it
        } elseif (is_string($value) && class_exists($value)) {
            $this->storeInContainer($key, new $value());

        } else {
            throw new \ErrorException("Invalid service: $value", 1);
        }
    }

    /**
     * Stores a service in the container.
     *
     * @param string $key The key of the service.
     * @param mixed $value The value of the service.
     */
    private function storeInContainer($key, $value)
    {
        self::$instance->container[strtolower($key)] = $value;
    }

    /**
     * Stores services in the container.
     * @param mixed $array
     * @throws \ErrorException
     * @return void
     */
    public function storeServices($array)
    {
        foreach ($array as $key => $value) {
            // If the value is an object, store it in the container
            if (is_object($value)) {
                $this->storeInContainer($key, $value);
            }
            // If the value is a callable, invoke it
            elseif (is_callable($value) && method_exists($value, '__invoke')) {
                $this->storeInContainer($key, $value($this));
            }
            // If the value is a string and the class exists, instantiate it 
            elseif (is_string($value) && class_exists($value) && is_subclass_of($value, '\\Facades')) {
                $this->storeInContainer($key, new $value());
            } else {
                throw new \ErrorException("Invalid service: $value", 1);
            }
        }
    }

    public function singleton($key, $value)
    {
        if (!isset(self::$singleton[$key])) {
            self::$singleton[$key] = $value;
        }
    }

    /**
     * Checks if a service exists in the container.
     *
     * @param string $key The key of the service.
     * @return bool True if the service exists, false otherwise.
     */
    public function has($key)
    {
        return isset($this->container[strtolower($key)]);
    }

    /**
     * Removes a service from the container.
     *
     * @param string $key The key of the service.
     */
    public function remove($key)
    {
        unset($this->container[strtolower($key)]);
    }

    public function make($newApp)
    {
        $classname = get_class($newApp);
        if (!class_exists($classname)) {
            throw new \ErrorException("
                Class $classname does not exist.
            ", 1);
        }

        self::$instance->container[strtolower($classname)] = $newApp;
        self::singleton($classname, new $classname);

        return self::$singleton[$classname];
    }

    /**
     * Magic method to retrieve a service from the container.
     *
     * @param string $key The key of the service.
     * @return mixed|null The retrieved service or null if not found.
     */
    public function __get($key)
    {
        return $this->get(mb_strtolower($key));
    }

    /**
     * Magic method to set a service in the container.
     *
     * @param string $key The key of the service.
     * @param $value The value of the service.
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Magic method to check if a service exists in the container.
     *
     * @param string $key The key of the service.
     * @return bool True if the service exists, false otherwise.
     */
    public function __isset($key)
    {
        return $this->has($key);
    }

    /**
     * Magic method to remove a service from the container.
     *
     * @param string $key The key of the service.
     */
    public function __unset($key)
    {
        $this->remove($key);
    }

    /**
     * Magic method to invoke a service from the container.
     *
     * @param mixed $value The value of the service.
     * @return mixed|null The invoked service or null if not found.
     */
    public function __invoke()
    {
        $vars = func_get_args();
    }

    /**
     * Load the configuration data from the config file
     * @param mixed $dir
     */
    public function loadConfig($dir = CONFIG_DIR): Config
    {
        self::$config = new Config($dir);
        return self::$config;
    }

    public static function setConfig($dir = CONFIG_DIR)
    {
        self::$config = new Config($dir);
    }

    /**
     * Get the configuration data from config file
     * @param mixed $file
     * @return mixed
     */
    public function getConfig($file)
    {
        return self::$config->getConfig($file);
    }

    public static function getAllConfig($dir = CONFIG_DIR): array
    {
        return self::$config?->getAllConfig()
            ?? (new Config())->getAllConfig();
    }

}