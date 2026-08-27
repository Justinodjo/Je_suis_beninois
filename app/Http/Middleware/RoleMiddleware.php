<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * ✅ Usage : ->middleware('role:admin')
     *            ->middleware('role:contributeur,admin')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = auth('api')->user();

        if (! $user) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        // Revérification du statut à chaque requête : un compte banni en cours
        // de route ne doit plus rien pouvoir faire, même avec un token valide
        if ($user->statut !== 'actif') {
            return response()->json(['message' => 'Compte ' . $user->statut], 403);
        }

        if (! in_array($user->role, $roles, true)) {
            return response()->json(['message' => 'Accès refusé — rôle insuffisant'], 403);
        }

        return $next($request);
    }
}