<?php

namespace Kernel;

class Config
{
    use \Singleton, ConfigTrait;
    private array $configFiles;
    private array $configData = [];
    private static array $configFolderLoaded = [];
    public string $configFolder;

    public function __construct(string $configFolder = null)
    {
        if ($this->wasConfigFolderAlreadyLoaded($configFolder)) {
            return;
        }

        if ($configFolder) {
            $this->loadNewConfig($configFolder);
        }
        
    }

    public function loadNewConfig($configFolder = null)
    {
        $this->configFiles = $this->retrieveFilesFromFolder($configFolder);
        $this->loadConfigFiles();
    }

    public function loadConfigFilesFromFilesOrFolder($configFiles = [], $configFolder = null)
    {

        $this->setConfigFiles($configFiles, $configFolder);
        $this->loadConfigFiles();
    }

    private function loadConfigFiles()
    {
        foreach ($this->configFiles as $file) {
            $fileNameWithoutExtension = pathinfo($file, PATHINFO_FILENAME);
            $this->assertConfigFileExists(pathinfo($file, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . $fileNameWithoutExtension . '.php');
            $this->setConfig($fileNameWithoutExtension);
        }
    }

    private function retrieveFilesFromFolder($configFolder = null): ?array
    {
        return glob($this->getValidatedConfigFolder($configFolder) . DIRECTORY_SEPARATOR . '*.php');
    }


    private function setConfig($fileName = null, $configFolder = null, $fileAndPath = null)
    {
        $configFile = $fileAndPath ?? (($configFolder ?? $this->configFolder) . DIRECTORY_SEPARATOR . $fileName . '.php');
        $this->assertConfigFileExists($configFile);
        if (file_exists($configFile)) {
            $this->configData[$fileName] = require_once $configFile;
        }
    }

    
}

class ConfigAdapter
{
    use \Singleton;
    public static $configFolders = [];

    public function loadFolders(array $folders)
    {
        self::$configFolders = $folders;
    }

    public function loadAndConfigure()
    {
        foreach (self::$configFolders as $folder) {
            $config = new Config($folder);
            $config->loadConfigFiles();
        }
    }

    

}


trait ConfigTrait
{

    private function getConfigFolder($folderToSet = null)
    {
        return $this->configFolder ??= $folderToSet;
    }

    private function setConfigFiles($configFiles = [], $configFolder = null)
    {
        if (!empty($configFiles)) {
            $this->configFiles = $configFiles;
        } else {
            $this->retrieveFilesFromFolder($configFolder);
        }
    }

    private function getExistingFile(string $file)
    {
        if (file_exists($file)) {
            return $file;
        } elseif (file_exists($file . '.php')) {
            return $file . '.php';
        }
    }

    private function setConfigFolder(string $configFolder): void
    {
        $this->configFolder = $configFolder;
        self::$configFolderLoaded[] = $configFolder;
    }

    private function wasConfigFolderAlreadyLoaded(string $configFolder): bool
    {
        return in_array($configFolder, self::$configFolderLoaded);
    }

    private function assertConfigFolderWasNotLoadedBefore($configFolder = null)
    {
        if (in_array($configFolder ?? $this->configFolder, self::$configFolderLoaded)) {
            throw new \Exception("Config folder $configFolder was already loaded");
        }
    }

    private function assertConfigFolderExists($configFolder = null)
    {
        if (!file_exists($configFolder ?? $this->configFolder)) {
            throw new \Exception("Config folder $configFolder does not exist");
        }

        if (!is_readable($configFolder ?? $this->configFolder)) {
            throw new \Exception("Config folder $configFolder is not readable");
        }
    }

    private function assertConfigFileExists($fileAndPath)
    {

        if (!file_exists($fileAndPath)) {
            throw new \Exception("Config file $fileAndPath does not exist");
        }

        if (!is_readable($fileAndPath)) {
            throw new \Exception("Config file $fileAndPath is not readable");
        }
    }


    private function assertConfigFolder($configFolder): void
    {
        $this->assertConfigFolderExists($configFolder);
        $this->assertConfigFolderWasNotLoadedBefore($configFolder);
    }

    private function getValidatedConfigFolder($configFolder): string
    {
        $this->assertConfigFolder($configFolder);
        $configFolder = $this->getConfigFolder($configFolder);
        return $configFolder;
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

    public function __toString(): string
    {
        return json_encode($this->configData);
    }
}