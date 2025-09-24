<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portal extends Model
{
    protected $fillable = [
        'name',
        'domain',
    ];

    public function articles() {
        return $this->hasMany(Article::class);
    }
}
