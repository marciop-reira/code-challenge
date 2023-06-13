<?php

use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\UserController;
use \App\Http\Controllers\AuthController;

Route::get('/', function () {
    return response()->json(['message' => 'Buzzvel Code Challenge']);
});

Route::post('/users', [UserController::class, 'store']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::fallback(function (){
    return response()->json(['message' => ''], 404);
});
