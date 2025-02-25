<?php

namespace App\Http\Middleware;

use App\Models\Level;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class RequireOwnLevelOrRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role, string $routeName): Response
    {
        $user = JWTAuth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $levelId = $request->route($routeName);

        if ($levelId == null) {
            return response()->json(['error' => 'Route has no user ID parameter "' . $routeName . '"'], 500);
        }

        $level = Level::find($levelId);

        if ($level == null) {
            return response()->json(['error' => 'No level exists for ID "' . $levelId . '"'], 404);
        } 

        $userId = $level->author_id;

        if (!$user->hasRole($role) && $user->id != $userId) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        
        return $next($request);
    }
}