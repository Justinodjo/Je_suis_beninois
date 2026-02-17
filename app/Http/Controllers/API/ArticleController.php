<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    /**
     * ✅ index() — filtres complets, pagination, eager loading optimisé
     */
    public function index(Request $request)
    {
        $query = Article::with([
            'user:id,name',
            'categories:id,nom,couleur,icone',
            'tags:id,nom',
            'medias:id,url,url_thumbnail,nom,type',
        ]);

        // ── Filtres ──────────────────────────────────────────────
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        } elseif (! auth()->check()) {
            // Visiteurs non authentifiés : seulement les publiés
            $query->where('statut', 'publié');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category_id')) {
            $query->whereHas('categories', fn ($q) => $q->where('id', $request->category_id));
        }

        if ($request->filled('tag_id')) {
            $query->whereHas('tags', fn ($q) => $q->where('id', $request->tag_id));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('extrait', 'like', "%{$search}%");
            });
        }

        // ── Pagination ───────────────────────────────────────────
        $perPage = min((int) $request->get('per_page', 12), 100);
        $articles = $query->latest()->paginate($perPage);

        return response()->json($articles);
    }

    /**
     * ✅ show() — charge les relations + incrémente les vues
     */
    public function show(Article $article)
    {
        // Vérifier que l'article est publié pour les non-authentifiés
        if ($article->statut !== 'publié' && ! auth()->check()) {
            return response()->json(['message' => 'Article non trouvé'], 404);
        }

        $article->load(['user:id,name,avatar', 'categories', 'tags', 'medias']);
        $article->increment('nb_vues');

        return response()->json(['data' => $article]);
    }

    /**
     * ✅ store() — slug auto, user_id depuis auth, medias supportés
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'        => 'required|string|max:255',
            'slug'         => 'nullable|string|unique:articles,slug',
            'extrait'      => 'nullable|string|max:500',
            'contenu'      => 'required|string',
            'statut'       => ['required', Rule::in(['brouillon', 'publié', 'archivé'])],
            // ✅ Types alignés avec le select du modal Blade
            'type'         => ['required', Rule::in(['article', 'tradition', 'patrimoine', 'interview', 'featured', 'galerie'])],
            'categories'   => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'tags'         => 'nullable|array',
            'tags.*'       => 'exists:tags,id',
            'medias'       => 'nullable|array',
            'medias.*'     => 'exists:media,id',
        ]);

        // ✅ Slug auto si absent
        $validated['slug'] = $validated['slug']
            ?? Str::slug($validated['titre']) . '-' . Str::lower(Str::random(5));

        // ✅ user_id depuis l'authentification (jamais depuis le client)
        $validated['user_id'] = auth()->id();

        // ✅ Date de publication
        if ($validated['statut'] === 'publié') {
            $validated['date_publication'] = now();
        }

        $article = Article::create($validated);

        if (! empty($validated['categories'])) {
            $article->categories()->attach($validated['categories']);
        }

        if (! empty($validated['tags'])) {
            $article->tags()->attach($validated['tags']);
        }

        if (! empty($validated['medias'])) {
            $article->medias()->attach($validated['medias']);
        }

        return response()->json([
            'message' => 'Article créé avec succès',
            'data'    => $article->load(['categories', 'tags', 'medias']),
        ], 201);
    }

    /**
     * ✅ update() — sync relations, droits vérifiés, date_publication auto
     */
    public function update(Request $request, Article $article)
    {
        // ✅ Vérification des droits
        if (auth()->id() !== $article->user_id && auth()->user()?->role !== 'admin') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'titre'        => 'sometimes|required|string|max:255',
            'slug'         => ['sometimes', 'required', 'string',
                              Rule::unique('articles', 'slug')->ignore($article->id)],
            'extrait'      => 'nullable|string|max:500',
            'contenu'      => 'sometimes|required|string',
            'statut'       => ['sometimes', Rule::in(['brouillon', 'publié', 'archivé'])],
            'type'         => ['sometimes', Rule::in(['article', 'tradition', 'patrimoine', 'interview', 'featured', 'galerie'])],
            'categories'   => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'tags'         => 'nullable|array',
            'tags.*'       => 'exists:tags,id',
            'medias'       => 'nullable|array',
            'medias.*'     => 'exists:media,id',
        ]);

        // ✅ Date de publication au premier passage en "publié"
        if (
            isset($validated['statut'])
            && $validated['statut'] === 'publié'
            && ! $article->date_publication
        ) {
            $validated['date_publication'] = now();
        }

        $article->update($validated);

        if (array_key_exists('categories', $validated)) {
            $article->categories()->sync($validated['categories'] ?? []);
        }

        if (array_key_exists('tags', $validated)) {
            $article->tags()->sync($validated['tags'] ?? []);
        }

        if (array_key_exists('medias', $validated)) {
            $article->medias()->sync($validated['medias'] ?? []);
        }

        return response()->json([
            'message' => 'Article mis à jour',
            'data'    => $article->load(['categories', 'tags', 'medias']),
        ]);
    }

    /**
     * ✅ destroy() — vérification droits + soft delete
     */
    public function destroy(Article $article)
    {
        if (auth()->id() !== $article->user_id && auth()->user()?->role !== 'admin') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $article->delete();

        return response()->json(['message' => 'Article supprimé']);
    }
}