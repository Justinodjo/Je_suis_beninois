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
     * Détail d'une interview + sidebar "dernières interviews" + suggestions liées
     */
    public function show($slug)
    {
        $interview = Article::with(['medias', 'categories', 'tags', 'user'])
            ->where('slug', $slug)
            ->where('type', 'interview')
            ->where('statut', 'publié')
            ->firstOrFail();

        $interview->increment('nb_vues');

        // Sidebar : dernières interviews publiées, hors interview courante
        $recentInterviews = Article::with('medias', 'categories')
            ->where('type', 'interview')
            ->where('statut', 'publié')
            ->where('id', '!=', $interview->id)
            ->latest()
            ->limit(4)
            ->get();

        // Suggestions : interviews partageant au moins une catégorie
        $suggestions = Article::with('medias', 'categories')
            ->where('type', 'interview')
            ->where('statut', 'publié')
            ->where('id', '!=', $interview->id)
            ->whereHas('categories', function ($q) use ($interview) {
                $q->whereIn('id', $interview->categories->pluck('id'));
            })
            ->limit(3)
            ->get();

        return view('pages.interviews.show', compact('interview', 'recentInterviews', 'suggestions'));
    }
}