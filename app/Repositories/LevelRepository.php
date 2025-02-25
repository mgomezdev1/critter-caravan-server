<?php

namespace App\Repositories;

use App\Models\Level;

class LevelRepository {
    public function index()
    {
        $levels = Level::all();
        return $levels;
    }

    public function show($id)
    {
        $level = Level::find($id);
        return $level;
    }

    public function store(array $level)
    {
        $level = Level::create($level);
        return $level;
    }

    public function update(array $data)
    {
        $level = Level::find($data['id']);
        if ($level == null) {return null;}
        $level->update($data);
        return $level;
    }

    public function destroy($id)
    {
        Level::destroy($id);
        return true;
    }
}