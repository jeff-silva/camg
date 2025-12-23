<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Controllers\Controller::register([
    Controllers\App\AppLoadController::class,
    Controllers\Mediamtx\MediamtxAuthController::class,
]);
