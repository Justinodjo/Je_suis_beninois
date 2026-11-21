<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    /**
     * Liste des commentaires.
     *
     * Filtres disponibles :
     * - statut
     * - article_id
     * - per_page
     */
    public function index(Request $request)
    {
        $query = Comment::with([
            'user:id,name,avatar',
            'article:id,titre,slug',
        ]);

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtre par article
        if ($request->filled('article_id')) {
            $query->where('article_id', $request->article_id);
        }

        // Pagination
        $perPage = min(
            max((int) $request->get('per_page', 15), 1),
            100
        );

        $comments = $query
            ->latest()
            ->paginate($perPage);

        return response()->json($comments);
    }

    /**
     * Afficher un commentaire.
     */
    public function show(Comment $comment)
    {
        $comment->load([
            'user:id,name,avatar',
            'article:id,titre,slug',
        ]);

        return response()->json([
            'data' => $comment,
        ]);
    }

    /**
     * Modifier le statut d'un commentaire.
     */
    public function update(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'statut' => [
                'required',
                Rule::in([
                    'en_attente',
                    'approuvé',
                    'rejeté',
                ]),
            ],
        ]);
        $wasApproved = $comment->statut === 'approuvé';
        $comment->update($validated);
        $isApproved = $comment->statut === 'approuvé';

        // nb_commentaires ne compte que les commentaires approuvés
        if ($wasApproved !== $isApproved) {
            $isApproved
                ? $comment->article()->increment('nb_commentaires')
                : $comment->article()->where('nb_commentaires', '>', 0)->decrement('nb_commentaires');
        }
        return response()->json([
            'message' => 'Commentaire mis à jour avec succès',
            'data' => $comment->load([
                'user:id,name,avatar',
                'article:id,titre,slug',
            ]),
        ]);
    }

    /**
     * Supprimer un commentaire.
     */
    public function destroy(Comment $comment)
    {
        if ($comment->statut === 'approuvé') {
            $comment->article()->where('nb_commentaires', '>', 0)->decrement('nb_commentaires');
        }

        $comment->delete();

        return response()->json([
            'message' => 'Commentaire supprimé avec succès',
        ]);
    }


    /**
 * ✅ Poster un commentaire sur un article — utilisateur JWT authentifié
 */
public function store(Request $request, Article $article)
{
    abort_if($article->statut !== 'publié', 404);
    $validated = $request->validate([
        'contenu' => 'required|string|max:1000',
    ]);

    $comment = $article->comments()->create([
        'user_id' => auth('api')->id(),
        'contenu' => $validated['contenu'],
        'statut'  => 'en_attente',
    ]);

   

    return response()->json([
        'message' => 'Commentaire envoyé, en attente de modération.',
        'data'    => $comment->load('user:id,name,avatar'),
    ], 201);
}
}