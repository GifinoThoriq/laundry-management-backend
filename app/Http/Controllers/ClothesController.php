<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clothes;
use App\Models\User;

class ClothesController extends Controller
{
    public function allClothes(){

        $clothes = Clothes::query()->with('user')->get();

        return response()->json([
            'meta' => [
                'status' => 200,
                'message' => 'Fetched successful',
            ],
            'data' => [
                'clothes' => $clothes
            ],
        ]);
    }

    public function addClothes(Request $request){
        $validated = $request->validate([
            'description' => 'required|string',
            'quantity' => 'required|integer',
            'type' => 'required|string',
            'color' => 'required|string',
            'fabric' => 'required|string'
        ]);

        
        $user = $request->user(); // ✅ Use authenticated user

        $clothes = $user->clothes()->create($validated);

        return response()->json([
            'meta' => [
                'status' => 200,
                'message' => 'Clothes Created',
            ],
            'data' => [
                'clothes' => $clothes
            ],
        ]);
    }

    public function customerClothes(Request $request){
        $user = $request->user();

        //only with user
        $clothes = $user->clothes()->with('user')->get();

        return response()->json([
            'meta' => [
                'status' => 200,
                'message' => 'Fetched successful',
            ],
            'data' => [
                'clothes' => $clothes
            ],
        ]);
    }
}
