<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class HandleValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Proceed with the request
            return $next($request);
        } catch (ValidationException $e) {
            // Handle validation error
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 400);
        }
    }
}