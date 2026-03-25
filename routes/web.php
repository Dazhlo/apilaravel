<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::resource('/api/carros', 'App\Http\Controllers\CarController');



Route::post('/api/registro', 'App\Http\Controllers\UserController@register');
Route::post('/api/acceso', 'App\Http\Controllers\UserController@login');
Route::get('/carros', [App\Http\Controllers\CarController::class, 'index']);
Route::post('/carros/crear', [App\Http\Controllers\CarController::class, 'store']);
Route::get('/carros/update', [App\Http\Controllers\CarController::class, 'update']);
Route::get('/api', [App\Http\Controllers\UserController::class, 'index']);