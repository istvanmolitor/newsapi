<?php

namespace App\Models;

use App\Enums\ArticleContentElementType;
use Illuminate\Database\Eloquent\Model;

class ArticleContentElement extends Model
{
    protected $fillable = [
        'article_id',
        'type',
        'content',
    ];

    protected $casts = [
        'type' => ArticleContentElementType::class,
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
