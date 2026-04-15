<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    
        $user = User::where('email', $credentials['email'])->first();
    
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Identifiants invalides'
            ])->withInput();
        }
    
        if ($user->statut !== 'actif') {
            return back()->withErrors([
                'email' => 'Compte désactivé ou banni'
            ]);
        }
    
        Auth::login($user);
    
        $user->update([
            'date_derniere_connexion' => now()
        ]);
    
        $redirectUrl = match ($user->role) {
            'admin' => route('dashboard.index'),
            'contributeur' => route('contribution.index'),
            default => route('home'),
        };
    
        if ($request->ajax()) {
            return response()->json([
                'message' => 'Connexion réussie',
                'redirect' => $redirectUrl
            ]);
        }
    
        return redirect()->intended($redirectUrl);
    }

    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate(); // invalide la session
        $request->session()->regenerateToken(); // 🔹 nouveau token CSRF
    
        return redirect()->route('home');
    }
}