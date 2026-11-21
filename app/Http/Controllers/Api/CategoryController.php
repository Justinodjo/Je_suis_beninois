<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all());
    }

    public function show(Category $category)
    {
        return response()->json($category);
    }

    /**
     * ✅ store() — réservé aux admins, slug auto-généré si absent
     */
    public function store(Request $request)
    {
        $authUser = Auth::guard('api')->user();

        if (!$authUser || $authUser->role !== 'admin') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'nom'         => 'required|string|max:255',
            'slug'        => 'nullable|string|unique:categories,slug',
            'description' => 'nullable|string',
            'couleur'     => 'nullable|string|max:20',
            'icone'       => 'nullable|string|max:50',
            'ordre'       => 'nullable|integer',
            'parent_id'   => 'nullable|exists:categories,id',
            'statut'      => 'nullable|in:actif,inactif',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['nom']);

        if (Category::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] .= '-' . Str::lower(Str::random(4));
        }

        $category = Category::create($validated);

        return response()->json($category, 201);
    }

    /**
     * ✅ update() — réservé aux admins
     */
    public function update(Request $request, Category $category)
    {
        $authUser = Auth::guard('api')->user();

        if (!$authUser || $authUser->role !== 'admin') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'nom'         => 'sometimes|required|string|max:255',
            'slug'        => ['nullable', 'string', Rule::unique('categories', 'slug')->ignore($category->id)],
            'description' => 'nullable|string',
            'couleur'     => 'nullable|string|max:20',
            'icone'       => 'nullable|string|max:50',
            'ordre'       => 'nullable|integer',
            'parent_id'   => 'nullable|exists:categories,id',
            'statut'      => 'nullable|in:actif,inactif',
        ]);
        // slug vide : on conserve l'ancien (la colonne est NOT NULL)
        if (array_key_exists('slug', $validated) && empty($validated['slug'])) {
            unset($validated['slug']);
        }

        $category->update($validated);

        return response()->json($category);
    }

    /**
     * ✅ destroy() — réservé aux admins
     */
    public function destroy(Category $category)
    {
        $authUser = Auth::guard('api')->user();

        if (!$authUser || $authUser->role !== 'admin') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $category->delete();

        return response()->json(['message' => 'Catégorie supprimée']);
    }
}
