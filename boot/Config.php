<?php

namespace Kernel;

class Config
{
    private array $configFiles;
    private array $configData = [];

    public function __construct
    (
        private string $configFolder = CONFIG_DIR
    ) {
        $this->loadConfigFiles();
    }

    public function loadConfigFiles()
    {
        $filesFromConfigFolder = scandir($this->configFolder);
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
}