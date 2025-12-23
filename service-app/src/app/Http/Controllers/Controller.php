<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

abstract class Controller
{
    public $url = '';
    public $methods = ['get'];
    public $middlewares = [];

    static function register(array $classes)
    {
        foreach ($classes as $class) {
            $controller = app($class);
            $router = Route::match($controller->methods, $controller->url, $class);
            $router->name(preg_replace('/Controller$/', '', class_basename($class)));
            $router->middleware($controller->middlewares);
        }
    }

    protected function body()
    {
        return [];
    }

    protected function query()
    {
        return [];
    }

    protected function params()
    {
        return [];
    }

    protected function response($code, $data)
    {
        return response()->json($data, $code);
    }
}
