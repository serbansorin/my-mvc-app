<?php



trait AppTrait
{
    #[\Attribute]
    public static function boot()
    {
        return new static();
    }

    public function __get($name)
    {
        if ($name === 'app') {
            return app();
        }
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

}