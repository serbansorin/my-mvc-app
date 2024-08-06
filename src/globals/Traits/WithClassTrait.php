<?php 

trait WithClassTrait
{

    private $instance = null;
    private static $with;

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public static function newInstance($getter, $args)
    {
        return new static::$with(...$args);
    }

    public function with($class)
    {
        static::$with = $class::class;
    }

    public static function __callStatic($method, $args)
    {
        if (method_exists(static::$with, $method)) {
            return call_user_func_array([static::$with, $method], $args);
        } elseif (method_exists($obj = new static::$with(), $method)) {
            return call_user_func_array([$obj, $method], $args);
        }
    }
}
