<?php
// app/Models/Category.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Category extends Model
{
    use HasFactory;
    // Champs autorisés pour l'insertion/édition
    protected $fillable = [
        'nom',
        'slug',
        'description',
        'couleur',
        'icone',
        'ordre',
        'parent_id',
        'statut',
    ];
    /**
     * Relation avec les articles (table pivot article_category)
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_category');
    }
    /**
     * Relation parent/enfant
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}