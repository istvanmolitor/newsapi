<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleArticleCollection extends Model
{
    protected $table = 'article_article_collection';

    public $timestamps = false; // only created_at exists

    protected $fillable = [
        'article_id',
        'article_collection_id',
        'created_at',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function collection()
    {
        return $this->belongsTo(ArticleCollection::class, 'article_collection_id');
    }
}
