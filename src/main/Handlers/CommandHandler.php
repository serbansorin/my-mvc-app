<?php

namespace Main\Handlers;

class CommandHandler
{
	// TODO make this file and move the make commands to a separate handler
	// private MakeCommandHandler $makeHandler;

	public function handle($argv)
	{
		if (count($argv) < 2) {
			$this->showHelp();
			return;
		}

		return match ($argv[1]) {
			'app:start' => $this->startServer(),
			'app:make' => $this->makeComponent($argv[2], $argv[3]),
			default => $this->showHelp(),
		};
	}

	private function startServer()
	{
		echo "Starting the application server...\n";
		exec('php .\\kernel\\server.php');
	}

	private function makeComponent($type, $name)
	{
		if (!$name) {
			echo "Please provide a name for the $type.\n";
			return;
		}

		switch ($type) {
			case '--model':
				$this->createModel($name);
				break;
			case '--controller':
				$this->createController($name);
				break;
			// Add other cases for Observer, Event, Websocket, Job
			default:
				echo "Unknown component type: $type\n";
				break;
		}
	}

	private function createModel($name)
	{
		$modelTemplate = "<?php\n\nclass $name extends Model\n{\n    // Model code here\n}\n";
		file_put_contents(__DIR__ . "/app/Models/$name.php", $modelTemplate);
		echo "Model $name created successfully.\n";
	}

	private function createController($name)
	{
		$controllerTemplate = "<?php\n\nclass {$name}Controller extends Controller\n{\n    // Controller code here\n}\n";
		file_put_contents(__DIR__ . "/app/Controllers/{$name}Controller.php", $controllerTemplate);
		echo "Controller {$name}Controller created successfully.\n";
	}

	private function showHelp()
	{
		echo "Usage:\n";
		echo "  php myapp app:start\n";
		echo "  php myapp app:make --model ModelName\n";
		echo "  php myapp app:make --controller ControllerName\n";
		// Add other usage examples for Observer, Event, Websocket, Job
	}
}