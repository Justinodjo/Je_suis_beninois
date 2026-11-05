<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'nom', 'titre', 'description', 'type',
        'url', 'url_thumbnail', 'chemin',
        'mime_type', 'taille', 'largeur', 'hauteur', 'duree',
        'alt_text', 'credits', 'user_id',
    ];

    protected $casts = [
        'taille'  => 'integer',
        'largeur' => 'integer',
        'hauteur' => 'integer',
        'duree'   => 'integer',
    ];

    protected function resolvePublicUrl(?string $value, ?string $path = null): ?string
    {
        if ($path) {
            return asset('storage/' . ltrim($path, '/'));
        }
        if (!$value) return null;
        if (str_starts_with($value, 'http')) return $value;

        $clean = preg_replace('#^/?(storage/)+#', '', ltrim($value, '/'));
        return asset('storage/' . $clean);
    }

    public function getUrlAttribute($value)
    {
        return $this->resolvePublicUrl($value, $this->attributes['chemin'] ?? null);
    }

    public function getUrlThumbnailAttribute()
    {
        $value = $this->attributes['url_thumbnail'] ?? null;
        if (!$value) return null;
        if (str_starts_with($value, 'http')) {
            $pos = strpos($value, '/storage/');
            if ($pos !== false && !str_contains($value, 'youtube') && !str_contains($value, 'vimeo') && !str_contains($value, 'unsplash')) {
                return asset(ltrim(substr($value, $pos), '/'));
            }
            return $value;
        }
        return $this->resolvePublicUrl($value);
    }

    public function getDisplayUrlAttribute()
    {
        return $this->type === 'video' ? $this->url_thumbnail : $this->url;
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_media')
                    ->withPivot(['ordre', 'legende']);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
