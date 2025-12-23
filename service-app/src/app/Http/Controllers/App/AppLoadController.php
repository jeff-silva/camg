<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppLoadController extends Controller
{
  public $url = '/app/load';
  public $methods = ['get'];
  public $middlewares = [];

  public function __invoke(Request $request)
  {
    $scope = new \stdClass;
    return $scope;
  }
}
