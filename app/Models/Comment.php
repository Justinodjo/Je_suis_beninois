<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'articles_id',
        'user_id',
        'contenu',
        'statut'
    ];

    public function article()
    {
        return $this->belongsTo(Article::class, 'articles_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
