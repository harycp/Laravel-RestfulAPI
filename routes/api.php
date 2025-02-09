<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/users/register', [UserController::class, 'register'])->name('register user');
Route::post('/users/login', [UserController::class, 'login'])->name('login user');