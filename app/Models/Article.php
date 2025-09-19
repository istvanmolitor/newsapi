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
        'main_image_src',
        'main_image_alt',
        'main_image_author',
        'scraped_at',
        'created_at',
        'updated_at',
    ];

    public function portal()
    {
        return $this->belongsTo(Portal::class);
    }

    public function articleContentElements()
    {
        return $this->hasMany(ArticleContentElement::class);
    }
}
