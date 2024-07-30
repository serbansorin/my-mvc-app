<?php

namespace Main\Loaders;

class CommandsLoader
{
    private $handler;
    private $commands = [];
    private $makeCommands = [];
    private $otherCommands = [];

    const CMD_DIR = __DIR__ . '/Commands';
    
    public function __construct()
    {
        $this->handler = new \Main\Handlers\CommandHandler();
        $this->parseCommands();
    }

    public function run($argv)
    {
        $commandKey = $argv[1] ?? null;
        if ($commandKey && isset($this->commands[$commandKey])) {
            $commandClass = $this->commands[$commandKey];
            $commandInstance = new $commandClass();
            $commandInstance->run($argv);
        } else {
            $this->showHelp();
        }
    }

    public function showHelp()
    {
        echo "Usage: php myapp.php [command] [options]\n";
        echo "Available commands:\n";
        foreach ($this->commands as $key => $command) {
            echo "  $key\n";
        }
    }

    private function parseCommands()
    {
        $files = scandir(self::CMD_DIR);
        foreach ($files as $file) {
            if ($this->onlyFilesThatEndWithCommand($file)) {
                $commandClass = require self::CMD_DIR . '/' . $file;
                $commandKey = strtolower(basename($file, 'Command.php'));
                $this->commands[$commandKey] = $commandClass;
            }
        }
    }

    private function onlyFilesThatEndWithCommand($file)
    {
        return substr($file, -11) === 'Command.php';
    }

    public function getMakeCommands()
    {
        return $this->makeCommands;
    }

    public function getOtherCommands()
    {
        return $this->otherCommands;
    }
}