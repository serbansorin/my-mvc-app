<?php

namespace Main\Traits;

trait DependsOnConfig {
    private $config = \Kernel\Config::getInstance();

    public function __get($name)
    {
        // if name has Config at the end, then return the config instance
        if (substr($name, -6) === 'Config') {
            $nameWithoutConfig = substr($name, 0, -6);
            return $this->config->getConfig($nameWithoutConfig);
        } else {
            // get the class name
            $thisTrait = new \ReflectionClass($this);
            $className = $thisTrait->getShortName();
            // if class name has create$className.Config at the end, then add property to config class and register with the service provider
            if (substr($className, -12) === 'create' . $className . 'Config') {
                $classNameWithoutConfig = substr($className, 0, -12);
                $this->config->setConfig($classNameWithoutConfig, $this);
                $this->config->register();
            }
            return $this->$name;
        }
    }
}