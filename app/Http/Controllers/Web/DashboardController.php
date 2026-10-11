<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
     public function index()
    {
        return view('dashboard.index');
    }
     public function articles()
    {
        return view('dashboard.articles');
    }
    
    public function categories()
    {
        return view('dashboard.categories');
    }

    public function tags()
    {
        return view('dashboard.tags');
    }

    public function media()
    {
        return view('dashboard.media');
    }

    public function stats()
    {
        return view('dashboard.stats');
    }

    /**
     * ✅ users() — réservé aux admins (double protection : middleware + contrôleur)
     */
    public function users()
    {
        if (auth()->user()?->role !== 'admin') {
            abort(403, 'Accès réservé aux administrateurs');
        }

        return view('dashboard.users');
    }
}
