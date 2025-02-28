<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController
{
    public function __construct(protected UserRepository $userRepository) {}

    public function index()
    {
        $users = $this->userRepository->index();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = $this->userRepository->show($id);
        return response()->json($user->load('roles'));
    }

    public function store(Request $request)
    {
        //Validate user input
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
            ]);
        } catch (ValidationException $e) {
            //Handle validation error
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 400);
        }

        //Create the user
        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        //Assign the "user" role by default
        $userRoles = Role::where('name', 'user');
        error_log(json_encode($userRoles));
        $user->roles()->attach($userRoles->first());

        return response()->json($user->load('roles'), 201);
    }

    public function update(Request $request, $id)
    {
        $user = $this->userRepository->show($id);
        $user->update($request->all());
        return response()->json($user->load('roles'), 200);
    }

    public function destroy($id)
    {
        $this->userRepository->show($id);
        return response()->json(null, 204);
    }
}