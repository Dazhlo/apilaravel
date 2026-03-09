<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/api/registro', [App\Http\Controllers\UserController::class, 'register']);
Route::post('/api/acceso', [App\Http\Controllers\UserController::class, 'login']);
Route::resource('/api/carros', App\Http\Controllers\CarController::class);