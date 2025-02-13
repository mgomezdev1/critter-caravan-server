<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository {
    public function index()
    {
        $users = User::all();
        return $users;
    }

    public function show($id)
    {
        $user = User::find($id);
        return $user;
    }

    public function store(array $user)
    {
        $user = User::create($user);
        return $user;
    }

    public function update(array $data)
    {
        $user = User::find($data['id']);
        if ($user == null) {return null;}
        $user->update($data);
        return $user;
    }

    public function destroy($id)
    {
        User::destroy($id);
        return true;
    }
}