<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\JwtCookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * login / refresh / logout : le JWT voyage dans un cookie HttpOnly.
 * Les requêtes AJAX du site (X-Requested-With: XMLHttpRequest) ne reçoivent PAS le token
 * dans le corps de la réponse ; les clients externes (mobile, Postman) le reçoivent
 * en plus du cookie et peuvent l'utiliser en Bearer.
 */
class CookieAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['message' => 'Email ou mot de passe incorrect.'], 401);
        }

        $user = Auth::guard('api')->user();

        if ($user->statut !== 'actif') {
            Auth::guard('api')->logout();

            return response()->json(['message' => 'Compte ' . $user->statut], 403);
        }

        $user->forceFill(['date_derniere_connexion' => now()])->save();

        return $this->respond($request, $token, $user);
    }

    public function refresh(Request $request)
    {
        try {
            $token = Auth::guard('api')->refresh();
            $user  = Auth::guard('api')->setToken($token)->user();
        } catch (\Throwable $e) {
            return $this->expired();
        }

        if (! $user || $user->statut !== 'actif') {
            return $this->expired();
        }

        return $this->respond($request, $token, $user);
    }

    public function logout()
    {
        try {
            Auth::guard('api')->logout();
        } catch (\Throwable $e) {
            // token déjà expiré/invalide : on supprime quand même le cookie
        }

        return response()->json(['message' => 'Déconnexion réussie'])
            ->withCookie(JwtCookie::forget());
    }

    private function expired()
    {
        return response()->json(['message' => 'Session expirée'], 401)
            ->withCookie(JwtCookie::forget());
    }

    private function respond(Request $request, string $token, $user)
    {
        $body = [
            'message'  => 'Connexion réussie',
            'redirect' => in_array($user->role, ['admin', 'contributeur'], true)
                ? route('dashboard.index')
                : route('home'),
            'user'     => $user->only(['id', 'name', 'email', 'role', 'avatar', 'bio', 'statut']),
        ];

        if (! $request->ajax()) {
            $body['token']      = $token;
            $body['token_type'] = 'Bearer';
            $body['expires_in'] = Auth::guard('api')->factory()->getTTL() * 60;
        }

        return response()->json($body)->withCookie(JwtCookie::make($token));
    }
}