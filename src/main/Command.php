<?php
namespace Main;

abstract class Command
{
	protected $arguments = [];
	protected $description = '';

	public function __construct($arguments = [])
	{
		$this->arguments = $arguments;
	}

	abstract public function handle();

	public function getDescription()
	{
		return $this->description;
	}

	public function getArguments()
	{
		return $this->arguments;
	}
}