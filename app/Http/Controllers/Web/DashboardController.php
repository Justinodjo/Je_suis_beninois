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


    public function media()
    {
        return view('dashboard.media');
    }

    public function stats()
    {
        return view('dashboard.stats');
    }

    /**
     * Page Utilisateurs. Le HTML n'est qu'une coquille : les données et les actions
     * sont protégées par l'API (role:admin). L'ancien test auth()->user() utilisait le
     * guard `web` (session), toujours vide avec le JWT, et renvoyait 403 à tout le monde.
     */
    public function users()
    {
      

        return view('dashboard.users');
    }
}
