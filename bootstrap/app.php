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
    ->withMiddleware(function (Middleware $middleware) { // <<< Find this section

        // Add your middleware alias here
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class, // Register 'role' alias
            // Add other aliases if needed, e.g., 'guest', 'auth' are often handled differently now or built-in
        ]);

        // You might also add global middleware or group middleware here if needed
        // Example: $middleware->web(append: [ ... ]);
        // Example: $middleware->group('admin', [ ... ]); // For defining middleware groups

    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Exception handling configuration
    })->create();
