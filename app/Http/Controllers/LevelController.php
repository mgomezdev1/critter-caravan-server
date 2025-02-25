<?php

namespace App\Http\Controllers;

use App\Http\Libraries\LevelPaginationParams;
use App\Models\Level;
use App\Models\User;
use App\Repositories\LevelRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LevelController
{
    public function __construct(protected LevelRepository $levelRepository) {}

    public function index()
    {
        $queryParams = request()->query();
        $params = new LevelPaginationParams($queryParams);

        $user = JWTAuth::user();
        $isAdmin = $this->isAdmin($user);

        $levels = Level::orderBy($params->getSortAttribute(), $params->sortAsc ? 'ASC' : 'DESC');

        if ($params->author != null) {
            $levels = $levels->where('author_id', '=', $params->author);
        }

        if ($params->maxVerification >= 0) {
            $levels = $levels->where('verification_level', '<=', $params->maxVerification);
        }
        if ($params->minVerification > 0) {
            $levels = $levels->where('verification_level', '>=', $params->minVerification);
        }

        if ($params->category != null) {
            $levels = $levels->where('category', 'LIKE', '%' . $params->category . '%');
        }

        $levels = $levels->where(function ($query) use ($user) {
            $query->where('private', '=', 0)
                  ->orWhere('author_id', '=', $user->id);
        });

        $levels = $levels->paginate($params->perPage);

        return response()->json($levels);
    }

    private function isAdmin(User $user) {
        foreach($user->roles as $role) {
            if ($role->name == 'admin') return true;
        }
    }

    public function show($id)
    {
        $level = $this->levelRepository->show($id);

        if (!$this->canAccess($level)) {
            return response()->json(['error' => 'Private level'], 403);
        }

        return response()->json($level);
    }

    private function canAccess(Level $level) {
        if ($level->private) {
            $user = JWTAuth::user();

            if ($level->author_id == $user->id) return true;

            if ($this->isAdmin($user)) return true;

            return false;
        }
        return true;
    }

    public function store(Request $request)
    {
        // Validate user input
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'private' => 'required|boolean',
                'thumbnail' => 'string',
                'category' => 'string|max:31',
                'world' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            // Handle validation error
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 400);
        }

        $user = JWTAuth::user();

        // Create the user
        $level = Level::create([
            'name' => $validated['name'],
            'private' => $validated['private'],
            'thumbnail' => $validated['thumbnail'] ?? '',
            'category' => $validated['category'] ?? '',
            'world' => $validated['world'],
            'verification_level' => 0,
            'author_id' => $user->id,
        ]);

        return response()->json($level, 201);
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'string|max:255',
                'private' => 'boolean',
                'thumbnail' => 'string',
                'category' => 'string|max:31',
                'world' => 'string',
            ]);
        } catch (ValidationException $e) {
            // Handle validation error
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 400);
        }

        $level = $this->levelRepository->show($id);

        if(!$level) {
            return response()->json(['message' => 'No level exists for ID "' . $id . '"'], 404);
        }

        $level->update($validated + [
            'verification_level' => 0
        ]);
        return response()->json($level, 200);
    }

    public function destroy($id)
    {
        $this->levelRepository->show($id);
        return response()->json(null, 204);
    }
}