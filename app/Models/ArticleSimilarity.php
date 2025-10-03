<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleSimilarity extends Model
{
    protected $fillable = [
        'article_id_1',
        'article_id_2',
        'similarity',
        'method',
        'computed_at',
    ];

    protected $casts = [
        'computed_at' => 'datetime',
        'similarity' => 'float',
    ];

    public function article1()
    {
        return $this->belongsTo(Article::class, 'article_id_1');
    }

    public function article2()
    {
        return $this->belongsTo(Article::class, 'article_id_2');
    }
}
