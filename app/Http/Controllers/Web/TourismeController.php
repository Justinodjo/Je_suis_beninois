<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;

class TourismeController extends Controller
{
    public function index()
    {
        $articles = Article::with(['medias', 'categories', 'user'])
            ->where('statut', 'publié')
            ->whereHas('categories', function($q) {
                $q->where('nom', 'LIKE', '%tourisme%');
            })
            ->latest()
            ->paginate(12);
        
        return view('pages.tourisme.index', compact('articles'));
    }
}