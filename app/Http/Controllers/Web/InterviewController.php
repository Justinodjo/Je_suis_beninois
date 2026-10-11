<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;

class InterviewController extends Controller
{
    /**
     * ✅ index() ajouté (manquant dans votre code original)
     */
 /**
     * Liste paginée des interviews, avec filtre optionnel par catégorie (?categorie=slug)
     */
    public function index()
    {
        $query = Article::with(['medias', 'categories', 'user'])
            ->where('type', 'interview')
            ->where('statut', 'publié');

        if (request()->filled('categorie')) {
            $query->whereHas('categories', function ($q) {
                $q->where('slug', request('categorie'));
            });
        }

        $interviews = $query->latest()->paginate(12)->withQueryString();

        // Catégories réellement utilisées par des interviews, pour le filtre
        $categories = Category::whereHas('articles', function ($q) {
            $q->where('type', 'interview')->where('statut', 'publié');
        })->get();

        return view('pages.interviews.index', compact('interviews', 'categories'));
    }


    /**
     * ✅ show() corrigé - 'publié' avec accent
     */
    public function show($slug)
    {
        $interview = Article::with(['medias', 'categories', 'user'])
            ->where('slug', $slug)
            ->where('type', 'interview')
            ->where('statut', 'publié') // ✅ CORRECTION : 'publié' avec accent (pas 'publie')
            ->firstOrFail();

        $recentInterviews = Article::with(['medias'])
            ->where('type', 'interview')
            ->where('id', '!=', $interview->id)
            ->where('statut', 'publié') // ✅ accent
            ->latest()
            ->limit(3)
            ->get();

        $suggestions = Article::with(['medias'])
            ->where('type', 'interview')
            ->where('id', '!=', $interview->id)
            ->where('statut', 'publié') // ✅ accent
            ->inRandomOrder()
            ->limit(3)
            ->get();

        $interview->increment('nb_vues');

        return view('pages.interviews.show', compact('interview', 'recentInterviews', 'suggestions'));
    }
}