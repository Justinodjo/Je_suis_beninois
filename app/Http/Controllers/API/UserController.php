<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * ✅ Liste des utilisateurs — réservé admin (voir routes/api.php)
     */
    public function index()
    {
        return response()->json(
            User::select('id', 'name', 'email', 'role', 'avatar', 'statut', 'created_at')
                ->paginate(20)
        );
    }

    public function show(User $user)
    {
        return response()->json(
            $user->only(['id', 'name', 'email', 'role', 'avatar', 'bio', 'statut'])
        );
    }

    /**
     * ✅ Inscription publique — SÉCURISÉ
     *    role et statut ne sont JAMAIS acceptés depuis le client :
     *    tout visiteur qui s'inscrit devient 'visiteur' / 'actif', point final.
     *    Seul un admin peut promouvoir quelqu'un en contributeur/admin (via update()).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'avatar'   => 'nullable|string',
            'bio'      => 'nullable|string',
        ]);

        $validated['password']         = Hash::make($validated['password']);
        $validated['role']             = 'visiteur';
        $validated['statut']           = 'actif';
        $validated['date_inscription'] = now();

        $user = User::create($validated);

        Mail::to($user->email)->send(new WelcomeMail($user));

        return response()->json(
            $user->only(['id', 'name', 'email', 'role', 'statut']),
            201
        );
    }

    /**
     * ✅ Mise à jour d'un utilisateur par un ADMIN (rôle, statut, etc.)
     *    Protégé par 'role:admin' au niveau des routes.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'email'    => ['sometimes', 'required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => ['sometimes', Rule::in(['visiteur', 'contributeur', 'admin'])],
            'avatar'   => 'nullable|string',
            'bio'      => 'nullable|string',
            'statut'   => ['sometimes', Rule::in(['actif', 'inactif', 'banni'])],
        ]);

        // Un admin ne peut pas se rétrograder lui-même par erreur / se bannir lui-même
        $authUser = Auth::guard('api')->user();
        if ($authUser->id === $user->id) {
            unset($validated['role'], $validated['statut']);
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json(
            $user->fresh()->only(['id', 'name', 'email', 'role', 'statut'])
        );
    }

    /**
     * ✅ Mise à jour du PROPRE profil — accessible à tout utilisateur connecté
     *    role/statut exclus : un utilisateur ne peut jamais se les changer lui-même
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::guard('api')->user();

        $validated = $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar'   => 'nullable|string',
            'bio'      => 'nullable|string',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json(
            $user->fresh()->only(['id', 'name', 'email', 'role', 'avatar', 'bio', 'statut'])
        );
    }

    public function destroy(User $user)
    {
        $authUser = Auth::guard('api')->user();

        // Défense en profondeur — déjà filtré par 'role:admin' en amont, mais on garde le check
        if (!$authUser || $authUser->role !== 'admin') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        if ($authUser->id === $user->id) {
            return response()->json(['message' => 'Vous ne pouvez pas vous supprimer vous-même'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé']);
    }
}