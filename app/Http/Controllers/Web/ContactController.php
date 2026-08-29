<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'     => 'required|string|max:120',
            'email'   => 'required|email|max:180',
            'sujet'   => 'nullable|string|max:180',
            'message' => 'required|string|max:3000',
        ]);

        Contact::create($validated);

        return redirect()->route('contact')->with('success', 'Votre message a bien été envoyé, merci !');
    }
}