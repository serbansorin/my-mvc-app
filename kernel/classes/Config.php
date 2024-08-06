<?php

namespace Kernel;

class Config
{
    use \Singleton;

    private array $configFiles;
    private array $configData = [];
    private int $configCount = 0;


    protected readonly array $mysql = [
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'test',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => ''
    ];
    
    protected readonly array $app = [
        'name' => 'App',
        'url' => 'http://localhost',
        'timezone' => 'UTC',
        'locale' => 'en',
        'env' => 'development',
        'debug' => true,
        'key' => 'SomeRandomString',
        'providers' => [
            // \Main\Providers\RouteServiceProvider::class,
            // \Main\Providers\ViewServiceProvider::class,
            // \Main\Providers\DatabaseServiceProvider::class,
            // \Main\Providers\SessionServiceProvider::class,
            // \Main\Providers\CookieServiceProvider::class,
            // \Main\Providers\HashServiceProvider::class,
            // \Main\Providers\Logger::class,
            // \Main\Providers\ErrorHandler::class,
            // \Main\Providers\CSRFServiceProvider::class,
        ]
    ];

    public function __construct
    (
        private string $configFolder = CONFIG_DIR
    ) {
        $this->loadConfigFiles();
    }

    public function loadConfigFiles()
    {
        $filesFromConfigFolder = scandir($this->configFolder);
        $this->configCount = count($filesFromConfigFolder);
        
        foreach ($filesFromConfigFolder as $file) {
            if ($file != '.' && $file != '..') {
                $this->setConfig($file);
            }
        }
    }

    private function setConfig($file)
    {
        $configFile = $this->configFolder . DIRECTORY_SEPARATOR . $file;
        if (file_exists($configFile)) {
            $fileNameWithoutExtension = pathinfo($file, PATHINFO_FILENAME);
            $this->configData[$fileNameWithoutExtension] = require_once $configFile;
        }
    }

    public function getConfig($file): array
    {
        return $this->configData[$file] ?? [];
    }

    public function getAllConfig(): array
    {
        return $this->configData;
    }

    public function __get($name)
    {
        return $this->getConfig($name);
    }

    public function __set($name, $value)
    {
        $this->configData[$name] = $value;
    }

    public function __isset($name): bool
    {
        return isset($this->configData[$name]);
    }

    public function __unset($name)
    {
        unset($this->configData[$name]);
    }

    public function __debugInfo(): array
    {
        return $this->configData;
    }

    public function __toString(): string
    {
        return json_encode($this->configData);
    }

    public function __invoke(): array
    {
        $callbackArgs = func_get_args();
        $config = Arr::flatten($this->configData);
    }
}