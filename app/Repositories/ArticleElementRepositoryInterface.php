<?php

namespace App\Repositories;

use App\Models\Article;

interface ArticleElementRepositoryInterface
{
    public function create(Article $article, string $type, string $content);
}
