<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InteractionController extends Controller
{
    /**
     * ✅ Toggle like — un clic like, un second clic délike
     */
    public function toggleLike(Article $article)
    {
        $userId = auth()->id();

        return DB::transaction(function () use ($article, $userId) {
            $existing = Like::where('article_id', $article->id)
                ->where('user_id', $userId)
                ->first();

            if ($existing) {
                $existing->delete();
                $article->decrement('nb_likes');
                $liked = false;
            } else {
                Like::create([
                    'article_id' => $article->id,
                    'user_id'    => $userId,
                ]);
                $article->increment('nb_likes');
                $liked = true;
            }

            return response()->json([
                'liked'    => $liked,
                'nb_likes' => $article->fresh()->nb_likes,
            ]);
        });
    }

    /**
     * ✅ Poster un commentaire — statut "en_attente" pour modération
     */
    public function storeComment(Request $request, Article $article)
    {
        $validated = $request->validate([
            'contenu' => 'required|string|max:1000',
        ]);

        $comment = $article->comments()->create([
            'user_id' => auth()->id(),
            'contenu' => $validated['contenu'],
            'statut'  => 'en_attente',
        ]);

        $article->increment('nb_commentaires');

        return response()->json([
            'message' => 'Commentaire envoyé, en attente de modération.',
            'data'    => $comment->load('user:id,name,avatar'),
        ], 201);
    }
}