<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Publicite;

class PubliciteClicController extends Controller
{
    /**
     * Compte le clic puis redirige vers le site de l'annonceur.
     * Le lien vient de la base (validé http/https côté admin) : pas de redirection ouverte.
     */
    public function clic(Publicite $publicite)
    {
        abort_unless($publicite->statut === 'actif' && $publicite->lien, 404);

        $publicite->increment('nb_clics');

        return redirect()->away($publicite->lien);
    }
}
