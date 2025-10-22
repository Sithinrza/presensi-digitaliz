<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware; // <--- Pastikan 'use' ini ada


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // --- TAMBAHKAN BLOK INI ---
        // Ini adalah pengganti $middlewareAliases
        $middleware->alias([
            'role' => \App\Http\Middleware\CekRole::class,
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,

            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
        // --- BATAS TAMBAHAN ---

    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ...
    })->create();
