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
        // Derrière un proxy TLS (Render, etc.) : sans ça, isSecure() reste faux et le cookie JWT n'est pas « Secure »
        $middleware->trustProxies(at: '*');

        // Le JWT est déjà signé ; le groupe api ne chiffre pas les cookies, donc on exclut aussi celui-ci du groupe web
        $middleware->encryptCookies(except: [\App\Http\Middleware\JwtFromCookie::NAME]);

        // JWT lu depuis le cookie HttpOnly (avant auth:api / role)
        $middleware->api(prepend: [\App\Http\Middleware\JwtFromCookie::class]);
        // ✅ CORS (OBLIGATOIRE pour API)
        $middleware->append(
            \Illuminate\Http\Middleware\HandleCors::class
        );

        // ✅ AJOUTER le groupe web par défaut (sessions, CSRF, cookies)
    $middleware->web(append: [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\JwtFromCookie::class,
    ]);

        $middleware->alias([
        'auth.optional' => \App\Http\Middleware\OptionalJwtAuth::class,
        'role'       => \App\Http\Middleware\RoleMiddleware::class,
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