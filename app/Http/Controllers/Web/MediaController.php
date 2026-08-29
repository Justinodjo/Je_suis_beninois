<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $medias = $query->paginate(24)->withQueryString();

        return view('pages.galerie', compact('medias'));
    }
}