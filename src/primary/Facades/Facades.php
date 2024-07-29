<?php

use \Main\Application;

class Facades
{
    protected static $app;

    protected static function getFacadeAccessor()
    {
        $class = (new \ClassTrainer(static::class))->getClassName();
        
        return strtolower(static::class);
    }

    protected static function init()
    {
        $app = Application::getInstance();
        self::$app = $app->get(static::getFacadeAccessor());

        return self::$app;
    }

    protected function __construct()
    {
        static::init();
    }

    public static function __callStatic($method, $args)
    {
        $app = static::checkIfMethodExists($method);

        if (static::isClassMethodStatic($app, $method)) {
            return get_class($app)::$method(...$args);
        }

        return \call_user_func_array([$app, $method], $args);
    }

    public function __call($method, $args)
    {
        $app = static::checkIfMethodExists($method);

        if (static::isClassMethodStatic($app, $method)) {
            return get_class($app)::$method(...$args);
        }

        return \call_user_func_array([$app, $method], $args);
    }

    static private function checkIfMethodExists($method)
    {
        $app = static::$app;

        if (!$app) {
            static::init();
        }

        if (!method_exists($app, $method)) {
            throw new \RuntimeException('Method ' . $method . ' does not exist.');
        }

        return $app;

    }

    private function isClassMethodStatic(string $class, string $method): bool
    {
        $reflection = new \ReflectionMethod($class, $method);
        return $reflection->isStatic();
    }

}