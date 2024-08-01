<?php


trait Singleton
{
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function reload()
    {
        $args = func_get_args();

        self::$instance = null;
        return new self(...$args);
    }
}