<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


/**
 * Connexion, rafraîchissement et déconnexion : voir CookieAuthController
 * (JWT dans un cookie HttpOnly). Ici ne reste que l'utilisateur courant.
 */
class AuthController extends Controller
{


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
                'statut',
                'date_derniere_connexion',
            ]),
        ]);
    }
}