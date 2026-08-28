<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * ✅ index() — pagination + compteur d'utilisations
     */
    public function index(Request $request)
    {
        $query = Tag::withCount('articles');

        if ($request->filled('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

        // Si per_page=0 ou absent on peut tout retourner pour les dropdowns
        if ($request->filled('per_page')) {
            $tags = $query->orderByDesc('articles_count')->paginate((int) $request->per_page);
        } else {
            $tags = $query->orderByDesc('articles_count')->get();
        }

        return response()->json($tags);
    }

    /**
     * ✅ show() — un seul tag
     */
    public function show(Tag $tag)
    {
        $tag->loadCount('articles');
        return response()->json($tag);
    }

    /**
     * ✅ store() — slug auto généré depuis le nom
     *    (plus besoin d'envoyer le slug depuis le front)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100|unique:tags,nom',
        ]);

        // ✅ Normaliser : minuscules, sans espaces
        $validated['nom']  = strtolower(trim($validated['nom']));
        // ✅ Slug auto (plus à envoyer depuis le client)
        $validated['slug'] = Str::slug($validated['nom']);

        // Sécurité : slug unique
        if (Tag::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] .= '-' . Str::lower(Str::random(4));
        }

        $tag = Tag::create($validated);

        return response()->json($tag, 201);
    }

    /**
     * ✅ update() — met à jour nom + slug ensemble
     */
    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'nom' => 'sometimes|required|string|max:100|unique:tags,nom,' . $tag->id,
        ]);

        if (isset($validated['nom'])) {
            $validated['nom']  = strtolower(trim($validated['nom']));
            $validated['slug'] = Str::slug($validated['nom']);
        }

        $tag->update($validated);

        return response()->json($tag);
    }

    /**
     * ✅ destroy() — suppression sécurisée
     */
    public function destroy(Tag $tag)
    {
        // Détacher des articles avant de supprimer
        $tag->articles()->detach();
        $tag->delete();

        return response()->json(['message' => 'Tag supprimé']);
    }
}