<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use DateInterval;
use DateTime;

class AuthController
{
    //Login method to authenticate user and issue JWT
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $login = $credentials['email'];
        $user = User::where('email', $login)->orWhere('username', $login)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = JWTAuth::attempt(['email' => $user->email, 'password' => $credentials['password']]);
        if ($token) {
            return $this->respondWithToken($token, $user);
        }

        $token = JWTAuth::attempt(['username' => $user->username, 'password' => $credentials['password']]);
        if ($token) {
            return $this->respondWithToken($token, $user);
        }

        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    //Logout method to invalidate the token
    public function logout()
    {
        try {
            //Invalidate the current JWT token
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(null, 200);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Failed to logout, please try again'
            ], 401);
        }
    }

    //Refresh method to refresh the JWT token
    public function refresh()
    {
        try {
            //Refresh the token and return it
            $user = JWTAuth::user();
            $newToken = JWTAuth::refresh(JWTAuth::getToken());
            return $this->respondWithToken($newToken, $user);
        } catch (JWTException $exception) {
            return response()->json([
                'message' => 'Failed to refresh token'
            ], 401);
        }
    }

    //Me method to get the authenticated user
    public function me()
    {
        try {
            //Get the currently authenticated user
            $user = JWTAuth::user();

            if ($user == null) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
            return response()->json($user->load('roles'));
        } catch (JWTException $e) {
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