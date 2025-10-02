<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'portal_id',
        'url',
        'hash',
        'title',
        'lead',
        'author',
        'list_image_src',
        'main_image_src',
        'main_image_alt',
        'main_image_author',
        'published_at',
        'scraped_at',
    ];

    public function portal()
    {
        return $this->belongsTo(Portal::class);
    }

    public function articleContentElements()
    {
        return $this->hasMany(ArticleContentElement::class);
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'article_keyword');
    }

    public function __toString()
    {
        return $this->url;
    }
}
