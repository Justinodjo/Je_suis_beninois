<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        // Les vidéos sont chargées à part : elles ne dépendent plus de la page de photos
        $videos = Media::where('type', 'video')->latest()->take(30)->get();

        // Les photos restent paginées
        $images = Media::where('type', 'image')->latest()->paginate(24)->withQueryString();

        return view('pages.galerie', compact('videos', 'images'));
    }
}
