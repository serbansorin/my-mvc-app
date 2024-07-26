<?php

namespace Main;

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

    /**
     * Application constructor.
     * Initializes the container with default services.
     */
    private function __construct()
    {
        // $this->container['request'] = new Request();
        // $this->container['response'] = new Response();
        $this->container = require __DIR__ . '/../config/services.php';
        $this->processContainer();
    }

    /**
     * Processes the container by resolving callable services.
     */
    private function processContainer()
    {
        foreach ($this->container as $key => $value) {

            if (is_string($value) && class_exists($value)) {
                $this->container[$key] = new $value();
            } else
            if (is_callable($value)) {
                $this->container[$key] = $value($this);
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
     *
     * @param string $key The key of the service.
     * @param \ApplicationInterface|null $value The value of the service.
     */
    public function set($key, $value = null)
    {
        if (empty($value)) {
            $tmp = ucfirst($key);

            if (class_exists('App\\' . $tmp)) {
                $value = 'App\\' . $tmp;
            } else if (class_exists('Main\\' . $tmp)) {
                $value = 'Main\\' . $tmp;
            }

            if (class_exists($value)) {
                $value = new $value();
            }
        }

        if (is_object($value)) {
            $this->storeInContainer($key, $value);
        } elseif (is_callable($value)) {
            $value = $value($this);
        }
    }

    private function storeInContainer($key, $value)
    {
        self::$instance->container[strtolower($key)] = $value;
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

    /**
     * Magic method to retrieve a service from the container.
     *
     * @param string $key The key of the service.
     * @return mixed|null The retrieved service or null if not found.
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Magic method to set a service in the container.
     *
     * @param string $key The key of the service.
     * @param \ApplicationInterface $value The value of the service.
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
}