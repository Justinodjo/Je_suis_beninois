<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Connexion utilisateur avec JWT
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'message' => 'Identifiants invalides'
            ], 401);
        }

        $user = Auth::guard('api')->user();

        if ($user->statut !== 'actif') {
            Auth::guard('api')->logout();

            return response()->json([
                'message' => 'Compte ' . $user->statut
            ], 403);
        }

        // Mettre à jour la dernière connexion
        $user->update([
            'date_derniere_connexion' => now()
        ]);

        return response()->json([
            'message' => 'Connexion réussie',

            'user' => $user->only([
                'id',
                'name',
                'email',
                'role',
                'avatar',
                'bio',
                'statut'
            ]),

            'token' => $token,
            'token_type' => 'Bearer',

            'expires_in' =>
                Auth::guard('api')->factory()->getTTL() * 60,
        ]);
    }

    /**
     * Utilisateur connecté
     */
    public function me()
    {
        $user = Auth::guard('api')->user();

        return response()->json([
            'user' => $user->only([
                'id',
                'name',
                'email',
                'role',
                'avatar',
                'bio',
                'statut'
            ])
        ]);
    }

    /**
     * Déconnexion JWT
     */
    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json([
            'message' => 'Déconnexion réussie'
        ]);
    }

    /**
     * Rafraîchir le token JWT
     */
    public function refresh()
    {
        $token = Auth::guard('api')->refresh();

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',

            'expires_in' =>
                Auth::guard('api')->factory()->getTTL() * 60,
        ]);
    }
}