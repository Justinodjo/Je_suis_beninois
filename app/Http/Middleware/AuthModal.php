<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthModal
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            // Utilisateur non connecté → redirige vers la page d'accueil avec modal
            return redirect('/')->with('showLoginModal', true);
        }

        return $next($request);
    }
}
