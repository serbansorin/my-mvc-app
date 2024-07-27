<?php

namespace Main\Accessors;

class Facades
{
    public static function getFacadeAccessor()
    {
        return 'make';
    }

    public static function __callStatic($method, $args)
    {
        $instance = app()->get(static::getFacadeAccessor());

        return $instance->$method(...$args);
    }

    public static function getClassName()
    {
        return get_called_class();
    }
}