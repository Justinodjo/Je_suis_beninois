<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;

class InterviewController extends Controller
{
    /**
     * ✅ index() ajouté (manquant dans votre code original)
     */
    public function index()
    {
        $interviews = Article::with(['medias', 'categories', 'user'])
            ->where('type', 'interview')
            ->where('statut', 'publié') // ✅ CORRECTION : accent
            ->latest()
            ->paginate(12);

        return view('pages.interviews.index', compact('interviews'));
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