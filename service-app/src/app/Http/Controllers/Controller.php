<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

abstract class Controller
{
    public $url = '';
    public $methods = ['get'];
    public $middlewares = [];

    static function register($class)
    {
        $controller = app($class);
        $router = Route::match($controller->methods, $controller->url, $class);
        // $router->name(get_called_class());
        // $router->middleware($controller->middlewares);
    }
}
