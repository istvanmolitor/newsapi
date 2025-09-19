<?php

namespace App\Models;

use App\Enums\ArticleContentElementType;
use Illuminate\Database\Eloquent\Model;
use stdClass;

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


    public function getContent(): array|string|stdClass
    {
        if($this->type === ArticleContentElementType::Paragraph || $this->type === ArticleContentElementType::Quote) {
            return (string)$this->content;
        }
        else {
            return json_decode($this->content);
        }
    }
}
