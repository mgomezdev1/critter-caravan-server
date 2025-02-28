<?php

use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/register', function () {
    return view('register');
});

Route::post('/store', function (Request $request, UserController $userController) {
    $response = $userController->store($request);

    return redirect('/register')->with('data', [
        'response' => $response->getData(true),
        'request' => $request->all()
    ]);
});

Route::get('/levels', function (LevelController $levelController) {
    $response = $levelController->index();

    return view('levels', ['data' => $response->getData(true)]);
})->name('levels');

Route::get('/search', function (Request $request) {
    //Convert form params to query params
    $queryParams = [];
    foreach ($request->only('name', 'per_page', 'sort', 'author', 'category', 'min_verification', 'max_verification') as $key => $value) {
        $queryParams[$key] = $value;
    }
    foreach ($request->only('sort_asc') as $key => $value) {
        $queryParams[$key] = $value == 'on';
    }

    return redirect()->route('levels', $queryParams);
});