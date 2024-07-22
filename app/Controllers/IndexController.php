<?php

namespace App\Controllers;

use App\Models\BaseModel;

class IndexController extends Controller
{
    public function index()
    {
        // Your logic for handling the index page request goes here
        // You can access the Request and Response objects from Swoole
        
        // Example usage of BaseModel
        $model = new BaseModel();
        $data = $model->getData();

        // Example rendering of the view
        $view = new View();
        $view->render('index', $data);
    }
}