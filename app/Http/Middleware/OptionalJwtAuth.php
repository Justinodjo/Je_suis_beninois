<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Authentification JWT facultative : si un Bearer valide est fourni, le guard
 * `api` devient le guard par défaut ; sinon la requête continue en anonyme.
 * (Sans cela, auth()->check() utilise le guard `web` et vaut toujours false.)
 */
class OptionalJwtAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->bearerToken()) {
            try {
                if (Auth::guard('api')->check()) {
                    Auth::shouldUse('api');
                }
            } catch (\Throwable $e) {
                // token invalide / expiré : on reste anonyme
            }
        }

        return $next($request);
    }
}