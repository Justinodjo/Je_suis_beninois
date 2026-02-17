<?php
// app/Models/Tag.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Tag extends Model
{
    use HasFactory;
    // Champs autorisés pour insertion / modification
    protected $fillable = [
        'nom',
        'slug',
        'nb_utilisations',
    ];
    /**
     * Relation avec les articles (table pivot article_tag)
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_tag');
    }
}