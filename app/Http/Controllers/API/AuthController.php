<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(AuthRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid Email or Password'], 401);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Login successfully',
            'data' => new AuthResource(auth()->user()),
        ]);
    }

    public function logout()
    {
        if (Auth::check()) {
            auth()->user()->tokens()->delete();
        }

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
