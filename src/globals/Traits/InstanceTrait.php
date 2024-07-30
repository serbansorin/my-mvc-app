<?php 

trait InstanceTrait
{

    private $instance = null;

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function __get($name)
    {
        if (!property_exists($this, $name)) {
            return $this->$name = app()->$name;
        }
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public static function instance()
    {
        return new static();
    }
}
