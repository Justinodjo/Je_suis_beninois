<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media'; // ✅ Table au singulier (votre migration crée 'media')

    protected $fillable = [
        'titre',
        'description',
        'type',
        // ✅ ATTENTION : votre migration utilise 'url' et 'url_thumbnail' (pas path/thumbnail_path)
        // Harmonisé avec la migration create_media_table.php
        'url',
        'url_thumbnail',
        'mime_type',
        'taille',
        'largeur',
        'hauteur',
        'duree',
        'alt_text',
        'credits',
        'user_id',
    ];

    protected $casts = [
        'taille'  => 'integer',
        'largeur' => 'integer',
        'hauteur' => 'integer',
        'duree'   => 'integer',
    ];

    /**
     * ✅ CORRECTION : La migration stocke l'URL directe (pas un path Storage)
     * Retourner l'URL telle quelle si elle commence par http, sinon via Storage
     */
     /**
     * ✅ Toujours retourner une URL absolue
     */
    public function getUrlAttribute($value): ?string
    {
        if (!$value && $this->chemin) {
            return Storage::url($this->chemin);
        }
        return $value;
    }

    public function getUrlThumbnailAttribute($value): ?string
    {
        if (!$value && $this->type === 'image' && $this->chemin) {
            return Storage::url($this->chemin);
        }
        return $value;
    }

    /**
     * Relation avec les articles
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_media')
                    ->withPivot(['ordre', 'legende']);
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}