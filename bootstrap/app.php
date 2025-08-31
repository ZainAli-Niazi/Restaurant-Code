<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register AdminAuthenticate middleware alias
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminAuthenticate::class,
        ]);

        // Redirect configurations
        $middleware->redirectTo(
            guests: '/admin/login',
            users: '/admin/dashboard'
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();