<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleElement extends Model
{
    protected $fillable = [
        'article_id',
        'type',
        'content',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
