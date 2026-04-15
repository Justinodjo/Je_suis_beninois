<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Page d'accueil avec slider et 10 derniers articles par catégorie
     */
    public function index()
    {
        // Articles en vedette pour le slider (5 derniers featured ou publiés)
        $featuredArticles = Article::with(['medias', 'categories', 'user'])
            ->where('statut', 'publié')
            ->where(function($q) {
                $q->where('type', 'featured')
                  ->orWhereHas('categories', function($query) {
                      $query->where('nom', 'À la une');
                  });
            })
            ->latest()
            ->limit(5)
            ->get();
        
        // Si pas assez d'articles featured, prendre les plus récents
        if ($featuredArticles->count() < 5) {
            $featuredArticles = Article::with(['medias', 'categories', 'user'])
                ->where('statut', 'publié')
                ->latest()
                ->limit(5)
                ->get();
        }
        
        // Toutes les catégories avec comptage
        $categories = Category::withCount(['articles' => function($q) {
                $q->where('statut', 'publié');
            }])
            ->orderBy('nom')
            ->get();
        
        // 10 derniers articles par catégorie
        $articlesByCategory = [];
        foreach ($categories as $category) {
            $articlesByCategory[$category->id] = Article::with(['medias', 'categories', 'user'])
                ->where('statut', 'publié')
                ->whereHas('categories', function($q) use ($category) {
                    $q->where('categories.id', $category->id);
                })
                ->latest()
                ->limit(10)
                ->get();
        }
        
        return view('pages.accueil', compact('featuredArticles', 'categories', 'articlesByCategory'));
    }
}