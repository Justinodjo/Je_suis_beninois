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
        * Page Utilisateurs : accès contrôlé par les middlewares de la route (auth:api + role:admin),
        * le JWT étant lu depuis le cookie HttpOnly (JwtFromCookie).
     */
    public function users()
    {
      

        return view('dashboard.users');
    }
}
