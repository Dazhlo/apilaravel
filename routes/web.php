<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::resource('/api/carros', 'App\Http\Controllers\CarController');

Route::post('/api/registro', 'App\Http\Controllers\UserController@register');
Route::post('/api/acceso', 'App\Http\Controllers\UserController@login');

Route::get('/api', [App\Http\Controllers\UserController::class, 'index']);