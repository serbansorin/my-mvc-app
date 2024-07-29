<?php

trait BypassPrivateTrait
{
    public function resolve($name)
    {
        return $this->app->make($name);
    }

    public function resolveIf($name, $default = null)
    {
        if ($this->app->bound($name)) {
            return $this->resolve($name);
        }

    }

    public function __get($name)
    {
        if ($name === 'app') {
            return app();
        }
        
        return $this->resolveIf($name);
    }

}