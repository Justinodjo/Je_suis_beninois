<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class JwtFromCookie
{
    public const NAME = 'jwt';

    public static function make(string $token): Cookie
    {
        return cookie(self::NAME, $token, (int) config('jwt.refresh_ttl', 20160),
            '/', null, request()->isSecure(), true, false, 'Lax');
    }

    public static function forget(): Cookie
    {
        return cookie()->forget(self::NAME);
    }

    public function handle(Request $request, Closure $next): Response
    {
        $cookieToken = $request->cookie(self::NAME);
        $refreshed   = null;

        if (! $request->bearerToken() && is_string($cookieToken) && $cookieToken !== '') {
            $safeMethod = in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true);

            // Anti-CSRF : écriture authentifiée par cookie => X-Requested-With obligatoire
            if (! $safeMethod
                && $request->header('X-Requested-With') !== 'XMLHttpRequest'
                && ! $request->is('api/v1/login', 'api/v1/register')) {
                return response()->json(['message' => 'Requête refusée'], 403);
            }

            $token = $cookieToken;

            // Page chargée avec un token expiré mais renouvelable : on le renouvelle
            if ($request->isMethod('GET') && ! $request->is('api/*')) {
                $token = $this->refreshIfExpired($cookieToken, $refreshed);
            }

            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        $response = $next($request);

        if ($refreshed) {
            $response->headers->setCookie(self::make($refreshed));
        }

        return $response;
    }

    private function refreshIfExpired(string $token, ?string &$refreshed): string
    {
        try {
            JWTAuth::setToken($token)->checkOrFail();

            return $token;
        } catch (TokenExpiredException $e) {
            try {
                $new = JWTAuth::setToken($token)->refresh();
                $refreshed = $new;

                return $new;
            } catch (\Throwable $e2) {
                return $token;
            }
        } catch (\Throwable $e) {
            return $token;
        }
    }
}