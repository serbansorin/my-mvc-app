<?php

namespace Main\Traits;

trait CreateClassConfig
{
    public function createClassConfig($name = null)
    {
        $class = new \ReflectionClass($name);
        $className = $class->getShortName();
        return config()->setConfig($className, $this);
    }
}