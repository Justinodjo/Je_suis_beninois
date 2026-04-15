<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Tableau de bord principal
    public function index()
    {
        return view('dashboard.index');
    }

    // Page Articles du dashboard
    public function articles()
    {
        // Ici tu peux récupérer les articles depuis la base si besoin
        // $articles = Article::all();
        return view('dashboard.articles'); // Assure-toi que ce fichier existe
    }

    // Page Catégories
    public function categories()
    {
        return view('dashboard.categories');
    }

    // Page Tags
    public function tags()
    {
        return view('dashboard.tags');
    }

    // Page Médias
    public function media()
    {
        return view('dashboard.media');
    }

    // Page Statistiques
    public function stats()
    {
        return view('dashboard.stats');
    }

    
    /**
     * ✅ NOUVEAU : gestion des utilisateurs
     */
    public function users()
    {
        return view('dashboard.users');
    }
}