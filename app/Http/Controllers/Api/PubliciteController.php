<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Publicite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/**
 * Gestion des publicités — réservé à l'admin.
 * Les routes sont dans le groupe middleware 'auth:api' + 'role:admin' (routes/api.php).
 */
class PubliciteController extends Controller
{
    public function index()
    {
        return response()->json(
            Publicite::orderBy('emplacement')->orderBy('ordre')->orderBy('id')->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(creating: true));

        $data['image'] = $request->file('image')->store('publicites', 'public');
        $data['statut'] = $data['statut'] ?? 'actif';
        $data['ordre']  = $data['ordre'] ?? 0;

        return response()->json(Publicite::create($data), 201);
    }

    /**
     * Mise à jour en POST (et non PUT) : PHP ne lit pas le multipart/form-data d'un PUT.
     */
    public function update(Request $request, Publicite $publicite)
    {
        $data = $request->validate($this->rules(creating: false));

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($publicite->image);
            $data['image'] = $request->file('image')->store('publicites', 'public');
        } else {
            unset($data['image']);
        }

        $publicite->update($data);

        return response()->json($publicite->fresh());
    }

    public function destroy(Publicite $publicite)
    {
        Storage::disk('public')->delete($publicite->image);
        $publicite->delete();

        return response()->json(['message' => 'Publicité supprimée.']);
    }

    private function rules(bool $creating): array
    {
        return [
            'nom'         => 'required|string|max:255',
            'emplacement' => ['required', Rule::in(array_keys(Publicite::EMPLACEMENTS))],
            // pas de SVG (risque XSS) : seulement des images raster
            'image'       => [$creating ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'lien'        => 'nullable|url:http,https|max:500',
            'texte_alt'   => 'nullable|string|max:255',
            'ordre'       => 'nullable|integer|min:0',
            'statut'      => 'nullable|in:actif,inactif',
            'date_debut'  => 'nullable|date',
            'date_fin'    => 'nullable|date|after_or_equal:date_debut',
        ];
    }
}
