<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClothesController;
use App\Http\Controllers\EmpController;
use App\Http\Controllers\UserController;

//Register User
Route::post('/register', [AuthController::class, 'register']);

//Login User
Route::post('/login', [AuthController::class, 'login']);

//get all roles
Route::middleware(['auth:sanctum', 'role:admin'])->get('/roles', [EmpController::class, 'roles']);

//register by admin
Route::middleware(['auth:sanctum', 'role:admin'])->post('/register-admin', [EmpController::class, 'registerAdmin']);

//get all users
Route::middleware(['auth:sanctum', 'role:admin'])->get('/users', [UserController::class, 'users']);

//get edit users
Route::middleware(['auth:sanctum', 'role:admin'])->post('/edit-user/{user}', [UserController::class, 'editUsers']);

//get all clothes
Route::middleware(['auth:sanctum'])->get('/all-clothes', [ClothesController::class, 'allClothes']);

//get user clothes
Route::middleware(['auth:sanctum', 'role:customer'])->get('/clothes', [ClothesController::class, 'customerClothes']);

//add clothes
Route::middleware(['auth:sanctum', 'role:customer'])->post('/add-clothes', [ClothesController::class, 'addClothes']);

