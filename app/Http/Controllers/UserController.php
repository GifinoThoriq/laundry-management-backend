<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
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

    public function editUsers(Request $request, User $user){

        //validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|exists:roles,name'
        ]);

        $user->name = $validated['name'];
        $user->syncRoles($validated['role']);

        $user->save();
 
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
