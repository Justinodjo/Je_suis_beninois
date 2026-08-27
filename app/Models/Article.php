<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // ✅ CORRECTION : SoftDeletes (votre migration a $table->softDeletes())

class Article extends Model
{
    use HasFactory, SoftDeletes; // ✅ SoftDeletes ajouté

    protected $fillable = [
        'titre',
        'slug',
        'extrait',
        'contenu',
        'user_id',
        'statut',
        'type',
        'nb_vues',
        'nb_likes',
        'nb_commentaires',
        'date_publication',
    ];

    protected $casts = [
        'date_publication' => 'datetime',
        'nb_vues'          => 'integer',
        'nb_likes'         => 'integer',
        'nb_commentaires'  => 'integer',
    ];

    // ✅ Scopes utiles
    public function scopePublished($query)
    {
        return $query->where('statut', 'publié'); // accent
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Relation avec l'utilisateur (auteur)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les catégories
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'article_category');
    }

    /**
     * Relation avec les tags
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tag');
    }

    /**
     * Relation avec les médias
     */
    public function medias()
    {
        return $this->belongsToMany(Media::class, 'article_media')
                    ->withPivot(['ordre', 'legende'])
                    ->orderByPivot('ordre');
    }

    /**
     * Relation avec les commentaires
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikedBy(?int $userId): bool
    {
        if (! $userId) {
            return false;
        }

        return $this->likes()->where('user_id', $userId)->exists();
    }
}