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
        // ✅ CORS (OBLIGATOIRE pour API)
        $middleware->append(
            \Illuminate\Http\Middleware\HandleCors::class
        );

        // ✅ AJOUTER le groupe web par défaut (sessions, CSRF, cookies)
    $middleware->web(append: [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    ]);

        $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'auth.modal' => \App\Http\Middleware\AuthModal::class,
        'contributeur' => \App\Http\Middleware\ContributeurMiddleware::class,
    ]);

        // ❌ PAS de Sanctum SPA
        // ❌ PAS de CSRF
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();