<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ValidationResult;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/users/create', function () {
    return view('create');
});
Route::post("users/store", function (UserRepository $userRepository, UserController $userController, Request $request) {
    $data = $request->all();

    $validationResult = $userController->validate($data);

    if (!$validationResult->success) {return response()->json($validationResult, 400);}

    $userRepository->store($data);

    return redirect('/users');
});
Route::get('/users', function (UserRepository $userRepository) {
    return view('read', [
        'users' => $userRepository->index()
    ]);
});
Route::get('/users/edit/{userId}', function (UserRepository $userRepository, string $userId) {
    $user = $userRepository->show($userId);

    if ($user == null) {return view('404');}

    return view('edit', [
        'user' => $user
    ]);
});
Route::get('/users/delete/{user}', function (UserRepository $userRepository, $user) {
    $userRepository->destroy($user);

    return redirect('/users');
});
Route::post("users/update", function (UserRepository $userRepository, UserController $userController, ValidationResult $validationResult, Request $request) {
    $data = $request->all();

    $validationResult = $userController->validate($data);

    if (!$validationResult->success) {return response()->json($validationResult, 400);}
    
    $user = $userRepository->update($data);

    if ($user == null) {return response()->json(null, 404);}

    return redirect('/users/edit/' . $user->id);
});