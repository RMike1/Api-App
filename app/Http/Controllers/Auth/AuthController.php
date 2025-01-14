<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);
        $user = User::create($validated);
        $token = $user->createToken($validated['email'])->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }
}
