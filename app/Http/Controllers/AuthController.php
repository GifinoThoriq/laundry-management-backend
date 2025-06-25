<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);


        return response()->json([
            'meta' => [
                'status' => 200,
                'message' => 'Register successful',
            ],
            'data' => [
                'user' => $user
            ],
        ]);

    }

    public function login(Request $request){
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //checking email
        $user = User::where('email', $validated['email'])->first();

        //checking password
        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'meta' => [
                    'status' => 401,
                    'message' => 'Invalid credentials',
                ],
            ], 401);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'meta' => [
                'status' => 200,
                'message' => 'Login successful',
            ],
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ]);
    }
}
