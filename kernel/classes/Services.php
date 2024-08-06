<?php

namespace Kernel;

class Services
{
	use \InstanceTrait;

	private function __construct()
	{
	}
	/**
	 * Register services
	 *
	 * @param array $services
	 * @return void
	 */
	public static function register($services)
	{
		foreach ($services as $key => $value) {
			$key = strtolower($key);

			// If the value is empty, try to instantiate a class with the same name
			if (empty($value)) {
				$tmp = ucfirst($key);
				if (class_exists("App\\$tmp")) {
					$value = "App\\$tmp";
				} elseif (class_exists("Main\\$tmp")) {
					$value = "Main\\$tmp";
				} else {
					// get all files in Facades directory
					$files = scandir(ROOT_DIR . '/src/globals/Facades');

					// check if file exists
					$facadesFile = array_filter($files, function ($file) use ($tmp) {
						return ($file === $tmp . '.php') || ($file === $tmp . 'Facade.php');
					})[0] ?? null;

					if (isEmpty($facadesFile)) { // if file not found
						throw new \ErrorException("Invalid service: $value", 1);
					} else {
						$value = require ROOT_DIR . '/src/globals/Facades/' . $facadesFile;
					}
				}
			}

			// If the value is an object, store it in the container
			if (is_object($value)) {
				Application::storeInContainer($key, $value);
			}
			// If the value is a callable, invoke it
			elseif (is_callable($value) && method_exists($value, '__invoke')) {
				Application::storeInContainer($key, $value(Application::getInstance()));
			}
			// If the value is a string and the class exists, instantiate it
			elseif (is_string($value) && class_exists($value)) {
				Application::storeInContainer($key, new $value());
			} else {
				throw new \ErrorException("Invalid service: $value", 1);
			}
		}
	}

	public static function get($key)
	{
		return Application::get($key);
	}

	public static function set($key, $value)
	{
		Application::set($key, $value);
	}

	public static function delete($key)
	{
		unset(Application::$$key);
	}
}

