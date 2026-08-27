<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Like;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    /**
     * ✅ Toggle like/unlike pour l'utilisateur JWT authentifié
     */
    public function toggle(Article $article)
    {
        $userId = auth('api')->id();

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
     * ✅ État initial du bouton (appelé au chargement de la page si un token existe)
     */
    public function status(Article $article)
    {
        return response()->json([
            'liked'    => $article->isLikedBy(auth('api')->id()),
            'nb_likes' => $article->nb_likes,
        ]);
    }
}