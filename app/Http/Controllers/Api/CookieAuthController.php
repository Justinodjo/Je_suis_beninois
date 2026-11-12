
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Middleware\JwtFromCookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class CookieAuthController extends Controller
{
    /**
     * Connexion : le JWT est placé dans un cookie HttpOnly.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $guard = Auth::guard('api');

        if (! $token = $guard->attempt($credentials)) {
            return response()->json([
                'message' => 'Email ou mot de passe incorrect.',
            ], 401);
        }

        $user = $guard->user();

        if (! $user || $user->statut !== 'actif') {
            $guard->logout();

            return response()->json([
                'message' => 'Votre compte n’est pas actif.',
            ], 403);
        }

        $user->forceFill([
            'date_derniere_connexion' => now(),
        ])->save();

        return $this->respond($request, $token, $user);
    }

    /**
     * Renouvellement du JWT.
     * Cette route doit être protégée par auth:api.
     */
    public function refresh(Request $request)
    {
        try {
            $guard = Auth::guard('api');

            $token = $guard->refresh();
            $user = $guard->setToken($token)->user();

            if (! $user || $user->statut !== 'actif') {
                return $this->expired();
            }

            return $this->respond($request, $token, $user);
        } catch (Throwable $e) {
            return $this->expired();
        }
    }

    /**
     * Déconnexion et suppression du cookie.
     */
    public function logout()
    {
        try {
            Auth::guard('api')->logout();
        } catch (Throwable $e) {
            // Le cookie sera supprimé même si le JWT est expiré.
        }

        return response()->json([
            'message' => 'Déconnexion réussie.',
        ])->withCookie(JwtFromCookie::forget());
    }

    /**
     * Réponse commune après connexion ou renouvellement.
     */
    private function respond(Request $request, string $token, $user)
    {
        $body = [
            'message' => 'Connexion réussie.',
            'redirect' => in_array(
                $user->role,
                ['admin', 'contributeur'],
                true
            ) ? route('dashboard.index') : route('home'),

            'user' => $user->only([
                'id',
                'name',
                'email',
                'role',
                'avatar',
                'bio',
                'statut',
            ]),
        ];

        // Les clients externes peuvent aussi récupérer le JWT
        // pour une authentification Bearer.
        if (! $request->ajax()) {
            $body['token'] = $token;
            $body['token_type'] = 'Bearer';
            $body['expires_in'] =
                Auth::guard('api')->factory()->getTTL() * 60;
        }

        return response()
            ->json($body)
            ->withCookie(JwtFromCookie::make($token));
    }

    /**
     * Réponse lorsque la session JWT n'est plus valide.
     */
    private function expired()
    {
        return response()->json([
            'message' => 'Session expirée. Veuillez vous reconnecter.',
        ], 401)->withCookie(JwtFromCookie::forget());
    }
}
