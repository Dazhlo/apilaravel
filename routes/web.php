<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

// Rutas de Usuario
Route::post('/api/registro', [UserController::class, 'register']);
Route::post('/api/acceso', [UserController::class, 'login']);

// CRUD de Carros (Resource ya incluye GET, POST, PUT, DELETE)
Route::resource('/api/cars', CarController::class);