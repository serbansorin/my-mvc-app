<?php

namespace Src;

use Swoole\Http\Request;
use Swoole\Http\Response;

class Application
{
    private static $instance;
    private $container = [];

    private function __construct()
    {
        // Initialize the container with default services
        $this->container['request'] = new Request();
        $this->container['response'] = new Response();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get($key)
    {
        return $this->container[strtolower($key)] ?? null;
    }

    public function set($key, $value)
    {
        $this->container[$key] = $value;
    }
}
