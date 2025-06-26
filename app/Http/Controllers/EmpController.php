<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmpController extends Controller
{

    public function users(Request $request){

        $query = User::query()->with('roles');

        //search by name, email, role
        if($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('roles', function ($roleQuery) use ($search) {
                        $roleQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        //multiple role
        if ($roleParam = $request->query('roles')) {

            $roles = explode(',', $roleParam);

            $query->whereHas('roles', function ($q) use ($roles) {
                $q->whereIn('name', $roles);
            });
        }
    
        // Pagination support
        $perPage = $request->query('per_page', 10);
        $users = $query->paginate($perPage)->withQueryString();

        return response()->json([
            'meta' => [
                'status' => 200,
                'message' => 'Filtered user list',
                'pagination' => [
                    'total' => $users->total(),
                    'current_page' => $users->currentPage(),
                    'per_page' => $users->perPage(),
                    'last_page' => $users->lastPage(),
                ],
            ],
            'data' => $users->items(),
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
