<?php

namespace Main;

class Commands
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
        $this->handler->handle($argv);
    }

    public function showHelp()
    {
        echo "Usage: php app.php [command] [options]\n";
        echo "Available commands:\n";
        echo "  app:start\t\tStart the application server\n";
        echo "  app:make\t\tCreate a new component\n";
    }

    public function showMakeHelp()
    {
        echo "Usage: php app.php app:make [component] [name]\n";
        echo "Available components:\n";
        echo "  --controller\tCreate a new controller\n";
        echo "  --model\tCreate a new model\n";
        echo "  --migration\tCreate a new migration\n";
        echo "  --event\tCreate a new event\n";
        echo "  --listener\tCreate a new listener\n";
        echo "  --middleware\tCreate a new middleware\n";
        echo "  --command\tCreate a new command\n";
        echo "  --job\tCreate a new job\n";
    }

    public function parseOtherCommandFiles()
    {

    }

    /**
     * Load commands files from CMD_DIR and parse the commands into commands array
     */
    public function parseCommands()
    {
        $files = scandir(self::CMD_DIR);
        foreach ($files as $file) {
            if ($this->onlyFilesThatEndWithCommand($file)) {
                $command = require self::CMD_DIR . '/' . $file;
                $this->commands = array_merge($this->commands, $command);
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