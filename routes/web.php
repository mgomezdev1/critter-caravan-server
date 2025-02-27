<?php

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
