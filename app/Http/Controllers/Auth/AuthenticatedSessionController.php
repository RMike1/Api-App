<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $user = User::where('email', $request->email)->first();
        $data = [
            'user'=> $user,
            'token' => $user->createToken($user->email)->plainTextToken,
        ];
        return response()->json($data, 201);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        $token = $request->user()->currentAccessToken();

        if ($token) {
            $token->delete();

            return response()->json([
                'message' => 'Logged out successfully',
            ], 200);
        }

        return response()->json([
            'message' => 'Unauthenticated.',
        ], 401);
    }
}