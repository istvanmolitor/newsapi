<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    protected $fillable = [
        'keyword',
    ];

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_keyword');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_keyword');
    }
}
