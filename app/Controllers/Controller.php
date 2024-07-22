<?php

namespace App\Controllers;

class Controller
{
    protected $request;
    protected $response;

    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function index()
    {
        // Controller logic for the index page
    }

    // Add other common controller methods here
}