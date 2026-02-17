<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;

class HomeController extends Controller
{
    public function index()
    {
        $articles = Article::with(['medias', 'categories', 'user'])
            // ✅ CORRECTION BUG CRITIQUE : 'publié' avec accent (= valeur réelle en BDD)
            // Votre SQL définit : enum('brouillon','publié','archivé')
            ->where('statut', 'publié')
            ->latest()
            ->limit(6)
            ->get();

        return view('pages.home', compact('articles'));
    }
}