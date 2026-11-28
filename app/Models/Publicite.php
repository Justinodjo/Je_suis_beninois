<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Publicite extends Model
{
    /** Les 3 emplacements du site : libellé, taille recommandée, ratio d'affichage. */
    public const EMPLACEMENTS = [
        'banniere_principale' => [
            'label' => 'Bannière principale (sous « À la une »)',
            'taille' => '970 × 120 px',
            'ratio' => '970 / 120',
        ],
        'laterale' => [
            'label' => 'Latérale (colonne « Aussi à la une »)',
            'taille' => '300 × 250 px',
            'ratio' => '300 / 250',
        ],
        'banniere_contenu' => [
            'label' => 'Bannière entre les contenus (avant le footer)',
            'taille' => '970 × 120 px',
            'ratio' => '970 / 120',
        ],
    ];

    protected $fillable = [
        'nom', 'emplacement', 'image', 'lien', 'texte_alt',
        'ordre', 'statut', 'date_debut', 'date_fin',
    ];

    protected $casts = [
        'date_debut' => 'date:Y-m-d',
        'date_fin'   => 'date:Y-m-d',
        'ordre'      => 'integer',
        'nb_clics'   => 'integer',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image);
    }

    /** Publicités actives ET dans leur période de diffusion. */
    public function scopeActives(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query->where('statut', 'actif')
            ->where(fn ($q) => $q->whereNull('date_debut')->orWhere('date_debut', '<=', $today))
            ->where(fn ($q) => $q->whereNull('date_fin')->orWhere('date_fin', '>=', $today));
    }

    public function scopeEmplacement(Builder $query, string $emplacement): Builder
    {
        return $query->where('emplacement', $emplacement)->orderBy('ordre')->orderBy('id');
    }
}
