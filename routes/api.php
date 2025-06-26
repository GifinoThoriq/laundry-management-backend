<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpController;

//Register User
Route::post('/register', [AuthController::class, 'register']);

//Login User
Route::post('/login', [AuthController::class, 'login']);

//get all roles
Route::middleware(['auth:sanctum', 'role:admin'])->get('/roles', [EmpController::class, 'roles']);

//register by admin
Route::middleware(['auth:sanctum', 'role:admin'])->post('/register-admin', [EmpController::class, 'registerAdmin']);

//get all employees
Route::middleware(['auth:sanctum', 'role:admin'])->get('/employees', [EmpController::class, 'employees']);

//get all customers
Route::middleware(['auth:sanctum', 'role:admin'])->get('/customers', [EmpController::class, 'customers']);