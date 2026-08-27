<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Media;

class CultureController extends Controller
{
    /**
     * ✅ Index culture
     */
    public function index()
    {
        $articles = Article::with(['medias', 'categories', 'user'])
            // ✅ CORRECTION : 'publié' avec accent (valeur réelle dans votre BDD SQL)
            ->where('statut', 'publié')
            ->whereIn('type', ['article', 'tradition', 'patrimoine'])
            ->latest()
            ->paginate(12);

        return view('pages.culture.index', compact('articles'));
    }

    /**
     * ✅ Traditions corrigée
     */
    public function traditions()
    {
        $articles = Article::with(['medias', 'categories'])
            ->where('type', 'tradition')
            ->where('statut', 'publié') // ✅ accent
            ->latest()
            ->get();

        $galleryMedia = Media::where('type', 'image')
            ->latest()
            ->limit(5)
            ->get();

        return view('pages.culture.traditions', compact('articles', 'galleryMedia'));
    }

    /**
     * ✅ Patrimoine corrigée
     */
    public function patrimoine()
    {
        $patrimoines = Article::with(['medias', 'categories'])
            ->where('type', 'patrimoine')
            ->where('statut', 'publié') // ✅ accent
            ->latest()
            ->get();

        return view('pages.culture.patrimoine', compact('patrimoines'));
    }

    /**
     * ✅ show() article individuel corrigé
     */
    public function show($slug)
    {
        $article = Article::with([
                'medias', 'categories', 'tags', 'user',
                'comments' => fn ($q) => $q->where('statut', 'approuvé')
                    ->with('user:id,name,avatar')
                    ->latest(),
            ])
            ->where('slug', $slug)
            ->where('statut', 'publié')
            ->firstOrFail();

        $article->increment('nb_vues');

        $relatedArticles = Article::with(['medias', 'categories'])
            ->where('id', '!=', $article->id)
            ->where('statut', 'publié')
            ->whereHas('categories', fn ($q) => $q->whereIn('id', $article->categories->pluck('id')))
            ->limit(3)
            ->get();

        return view('pages.culture.article', compact('article', 'relatedArticles'));
    }
}