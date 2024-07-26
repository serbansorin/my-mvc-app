<?php

namespace Main;

class Controller
{
    protected $request;
    protected $response;
    private $view;

    public function __construct()
    {
        $this->view = app()->view;
    }

    public function index()
    {
        // Controller logic for the index page
    }

    public function __invoke()
    {
        return $this->index();
    }

    public function __get($name)
    {
        return app()->$name;
    }
}