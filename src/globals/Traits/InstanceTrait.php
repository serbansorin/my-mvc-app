<?php 

trait InstanceTrait
{

    private $instance = null;
    static $getter;

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public static function newInstance($getter, $args)
    {
        return new static::$getter(...$args);
    }

    public function setGetter($class)
    {
        static::$getter = $class::class;
    }

    public static function __callStatic($method, $args)
    {
        if (method_exists(static::$getter, $method)) {
            return static::$getter::$method(...$args);
        }
    }
}
