<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Login method to authenticate user and issue JWT
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        // Check if credentials are correct
        if ($token = JWTAuth::attempt($credentials)) {
            $user = User::where('email', 'like', $credentials["email"])->first();
            return $this->respondWithToken($token, $user);
        }

        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    // Logout method to invalidate the token
    public function logout(Request $request)
    {
        try {
            // Invalidate the current JWT token
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(null, 200);
        } catch (JWTException $exception) {
            return response()->json([
                'message' => 'Failed to logout, please try again'
            ], 401);
        }
    }

    // Refresh method to refresh the JWT token
    public function refresh()
    {
        try {
            // Refresh the token and return it
            $user = JWTAuth::user();
            $newToken = JWTAuth::refresh(JWTAuth::getToken());
            return $this->respondWithToken($newToken, $user);
        } catch (JWTException $exception) {
            return response()->json([
                'message' => 'Failed to refresh token'
            ], 401);
        }
    }

    // Me method to get the authenticated user
    public function me()
    {
        try {
            // Get the currently authenticated user
            $user = JWTAuth::user();

            if ($user == null) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
            return response()->json($user->load('roles'));
        } catch (JWTException $exception) {
            return response()->json([
                'message' => 'Unable to fetch user data'
            ], 500);
        }
    }

        /**
     * Get the token response array structure.
     *
     * @param  string $token
     * @param  User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(string $token, User $user)
    {
        $duration = auth()->factory()->getTTL() * 60;
        $expires = new DateTime();
        $expires->add(new DateInterval('PT' . $duration . 'S'));
        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'bearer',
            'expires' => $expires->format("c"),
        ]);
    }
}