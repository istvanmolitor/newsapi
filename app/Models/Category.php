<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'order',
    ];

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'category_keyword');
    }
}
