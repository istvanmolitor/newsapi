<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'portal_id',
        'title',
        'slug',
        'original_url',
        'lead',
        'author',
        'main_image_src',
        'main_image_alt',
        'main_image_author',
        'content',
        'created_at',
        'updated_at',
    ];

    public function portal()
    {
        return $this->belongsTo(Portal::class);
    }

    public function articleElements()
    {
        return $this->hasMany(ArticleElement::class);
    }
}
