<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LevelController;
use App\Http\Middleware\RequireOwnLevelOrRole;
use App\Http\Middleware\RequireRole;
use App\Http\Middleware\RequireSelfOrRole;
use Illuminate\Support\Facades\Route;

Route::group([

    'middleware' => 'api',
    'prefix' => 'users'

], function ($router) {

    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update'])->middleware(RequireSelfOrRole::class.':admin,id');
    Route::delete('/{id}', [UserController::class, 'destroy'])->middleware(RequireRole::class.':admin');

});

Route::group([

    'middleware' => 'api',
    'prefix' => 'levels'

], function ($router) {

    Route::get('/', [LevelController::class, 'index']);
    Route::get('/{id}', [LevelController::class, 'show']);
    Route::post('/', [LevelController::class, 'store']);
    Route::put('/{id}', [LevelController::class, 'update'])->middleware([RequireOwnLevelOrRole::class.':admin,id']);
    Route::delete('/{id}', [LevelController::class, 'destroy'])->middleware(RequireOwnLevelOrRole::class.':admin,id');

});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('/register', [UserController::class, 'store']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);
});
