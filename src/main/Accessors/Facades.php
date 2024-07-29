<?php

namespace Main\Accessors;


abstract class Facades
{
    public function __construct()
    {
        //
    }
    public function __get($key)
    {
        return app()->get($key);
    }
    
    public static function getFacadeAccessor()
    {
        return 'make';
    }

    public static function __callStatic($method, $args)
    {
        $instance = app()->get(static::getFacadeAccessor());

        return $instance->$method(...$args);
    }

}