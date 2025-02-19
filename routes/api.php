<?php

use App\Http\Controllers\AddressController;
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
    Route::get('/contacts', [ContactController::class, 'search'])->name('search contact');
    Route::get('/contacts/{id}', [ContactController::class, 'get'])->where('id', '[0-9]+')->name('get contacts');
    Route::patch('/contacts/{id}', [ContactController::class, 'update'])->where('id', '[0-9]+')->name('update contact');
    Route::delete('/contacts/{id}', [ContactController::class, 'remove'])->where('id', '[0-9]+')->name('delete contact');


    Route::post('/contacts/{idContact}/addresses', [AddressController::class, 'create'])->where('idContact', '[0-9]+')->name('create address');
    Route::get('/contacts/{idContact}/addresses/{idAddress}', [AddressController::class, 'get'])->where('idContact', '[0-9]+')->where('idAddress', '[0-9]+')->name('get address');
    Route::put('/contacts/{idContact}/addresses/{idAddress}', [AddressController::class, 'update'])->where('idContact', '[0-9]+')->where('idAddress', '[0-9]+')->name('update address');
    Route::delete('/contacts/{idContact}/addresses/{idAddress}', [AddressController::class, 'delete'])->where('idContact', '[0-9]+')->where('idAddress', '[0-9]+')->name('delete address');
});