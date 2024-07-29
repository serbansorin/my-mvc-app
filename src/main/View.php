<?php

namespace Main;

use \Main\Engine\TemplateEngine;

class View
{
    private static $templateEngine;

    public static function initialize($templateDir)
    {
        self::$templateEngine = new TemplateEngine($templateDir);
        return new self();
    }

    public static function render($template, $data = [])
    {
        if (!self::$templateEngine) {
            throw new \Exception("View class has not been initialized. Call View::initialize() first.");
        }
        
        return self::$templateEngine->render($template, $data);
    }

    public function __call($method, $args)
    {
        return match ($method) {
            'render' => self::render(...$args),
            default => throw new \Exception("Method $method does not exist."),
        };
    }

    public function __invoke()
    {
        \Main\View::initialize(ROOT_DIR . '/app/views');
    }
}