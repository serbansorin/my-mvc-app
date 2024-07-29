<?php

namespace Main;

class Config
{
    static public $config = array();

    public $config_file = "";

    public $config_dir = "";

    public $config_path = "";


    public function __construct($config_file = "config.php", $config_dir = "config")
    {
        $this->config_file = $config_file;
        $this->config_dir = $config_dir;
        $this->config_path = $this->config_dir . "/" . $this->config_file;
        $this->load();
    }

    public function load()
    {
        if (file_exists($this->config_path)) {
            self::$config = require $this->config_path;
        } else {
            throw new \Exception("Config file not found: " . $this->config_path);
        }
    }

    static public function get($key, $default = null)
    {
        return array_key_exists($key, self::$config) ? self::$config[$key] : $default;
    }
    
    static public function set($key, $value)
    {
        self::$config[$key] = $value;
    }



}