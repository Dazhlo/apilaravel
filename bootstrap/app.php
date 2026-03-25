<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
    $middleware->validateCsrfTokens(except: [
        'api/*',
        'api/registro',
        'api/acceso',
        'api/carros',
        'api/carros/*', // El comodín ayuda para subrutas
        'carros/crear',
        'carros/*',
        'api/cars/*'
    ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();