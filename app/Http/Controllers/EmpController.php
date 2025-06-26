<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmpController extends Controller
{

    public function customers(){
        $users = User::with('roles')
            ->whereHas('roles', function($query) {
                $query->where('name', 'customer');
            })
            ->get();

        return response()->json([
            'meta' => [
                'status' => 200,
                'message' => 'Fetched successful',
            ],
            'data' => [
                'users' => $users
            ],
        ]);
    }

    public function employees(){
        $users = User::with('roles')
            ->whereDoesntHave('roles', function($query) {
                $query->where('name', 'customer');
            })
            ->get();

        return response()->json([
            'meta' => [
                'status' => 200,
                'message' => 'Fetched successful',
            ],
            'data' => [
                'users' => $users
            ],
        ]);
    }

    public function roles(){
        $roles = Role::all();

        return response()->json([
            'meta' => [
                'status' => 200,
                'message' => 'Fetched successful',
            ],
            'data' => [
                'roles' => $roles
            ],
        ]);
    }

    public function registerAdmin(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        $user->assignRole($validated['role']);

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
}
