<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

//Register User
Route::post('/register', [AuthController::class, 'register']);