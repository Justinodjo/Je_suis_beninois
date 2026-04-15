<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    public function __construct()
    {
        // L'utilisateur doit être connecté
        $this->middleware('auth');
    }

    public function index()
    {
        // Vérifier que c'est un contributeur
        if (auth()->user()->role !== 'contributeur') {
            abort(403, 'Accès non autorisé');
        }

        return view('pages.contribution.index');
    }
}