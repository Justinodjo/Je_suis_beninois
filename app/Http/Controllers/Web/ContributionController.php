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
        return view('pages.contribution.index');
    }
}
