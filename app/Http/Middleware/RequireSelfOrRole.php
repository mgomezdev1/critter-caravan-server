<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class RequireSelfOrRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role, string $routeName): Response
    {
        $user = JWTAuth::user();
        $userId = $request->route($routeName);

        if ($userId == null) {
            return response()->json(['error' => 'Route has no user ID parameter "' . $routeName . '"'], 500);
        } 

        if (!$user || (!$user->hasRole($role) && $user->id != $userId)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        
        return $next($request);
    }
}