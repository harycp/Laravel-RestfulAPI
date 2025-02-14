<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/users/register', [UserController::class, 'register'])->name('register user');
Route::post('/users/login', [UserController::class, 'login'])->name('login user');

Route::middleware(ApiAuthMiddleware::class)->group(function(){
    Route::get('/users/current', [UserController::class, 'get'])->name("get user");
    Route::patch('/users/current', [UserController::class, 'update'])->name('update user');
    Route::delete('/users/logout', [UserController::class, 'logout'])->name('logout user');


    Route::post('/contacts', [ContactController::class, 'create'])->name('create contact');
    Route::get('/contacts/{id}', [ContactController::class, 'get'])->where('id', '[0-9]+')->name('get contacts');
    Route::patch('/contacts/{id}', [ContactController::class, 'update'])->where('id', '[0-9]+')->name('update contact');
});