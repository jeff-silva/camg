<?php

namespace App\Http\Controllers\Mediamtx;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MediamtxAuthController extends Controller
{
  public $url = '/mediamtx/auth';
  public $methods = ['post'];
  public $middlewares = [];

  public function __invoke(Request $request)
  {
    $scope = new \stdClass;

    parse_str($request->post('query'), $query);
    $query = (object) array_merge(['token' => ''], $query ?? []);
    \LogHelper::prepend($query);

    if ($query->token != 'aaa') {
      return $this->response(401, ['Erro']);
    }

    return $scope;
  }
}
