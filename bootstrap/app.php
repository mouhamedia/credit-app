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
        // Middlewares globaux si nécessaire
        // $middleware->add(\App\Http\Middleware\TrustProxies::class);

        // Middlewares de route personnalisés
        $middleware->alias([
            'admin'       => \App\Http\Middleware\AdminMiddleware::class,
            'boutiquier'  => \App\Http\Middleware\BoutiquierMiddleware::class,
            'checkStatus' => \App\Http\Middleware\CheckBoutiquierStatus::class,
            'client'      => \App\Http\Middleware\ClientAuth::class,  // AJOUTÉ ICI
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Gestion des exceptions personnalisée
    })
    ->create();