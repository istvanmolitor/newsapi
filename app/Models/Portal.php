<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portal extends Model
{
    protected $fillable = [
        'name',
        'domain',
        'rss',
    ];

    public function articles() {
        return $this->hasMany(Article::class);
    }
}
