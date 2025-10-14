<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Debes colocar el registro del alias de tu middleware aquí:
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminRoleMiddleware::class, // ¡Esta es la línea clave!
        ]);
        
        // Si ya tienes otros middlewares, agrégalos también dentro del alias
        
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();