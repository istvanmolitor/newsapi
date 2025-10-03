<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleCollection extends Model
{
    protected $fillable = [
        'title',
        'lead',
        'is_same',
    ];

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_article_collection');
    }
}
