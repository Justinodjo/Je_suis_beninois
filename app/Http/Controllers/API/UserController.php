<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // ✅ CORRECTION : import Hash au lieu de \Hash
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        // ✅ CORRECTION : ne jamais retourner les mots de passe, paginer
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // ✅ min:8 + confirmation
            'role'   => ['required', Rule::in(['visiteur', 'contributeur', 'admin'])],
            'avatar' => 'nullable|string',
            'bio'    => 'nullable|string',
            'statut' => ['nullable', Rule::in(['actif', 'inactif', 'banni'])],
        ]);

        $validated['password'] = Hash::make($validated['password']); // ✅ Hash::make()
        $user = User::create($validated);

        return response()->json(
            $user->only(['id', 'name', 'email', 'role', 'statut']),
            201
        );
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'   => 'sometimes|required|string|max:255',
            'email'  => ['sometimes', 'required', 'email',
                         Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role'   => ['sometimes', Rule::in(['visiteur', 'contributeur', 'admin'])],
            'avatar' => 'nullable|string',
            'bio'    => 'nullable|string',
            'statut' => ['sometimes', Rule::in(['actif', 'inactif', 'banni'])],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']); // ✅
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json(
            $user->fresh()->only(['id', 'name', 'email', 'role', 'statut'])
        );
    }

    public function destroy(User $user)
    {
        // ✅ CORRECTION : vérification auth() sécurisée
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        // ✅ Ne pas se supprimer soi-même
        if (auth()->id() === $user->id) {
            return response()->json(['message' => 'Vous ne pouvez pas vous supprimer vous-même'], 403);
        }

        $user->delete();
        return response()->json(['message' => 'Utilisateur supprimé']);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnexion réussie']);
    }
}