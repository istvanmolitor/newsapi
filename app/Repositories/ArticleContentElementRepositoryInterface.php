<?php

namespace App\Repositories;

use App\Enums\ArticleContentElementType;
use App\Models\Article;
use App\Models\ArticleContentElement;

interface ArticleContentElementRepositoryInterface
{
    public function create(Article $article, ArticleContentElementType $type, array $content): ArticleContentElement;

    public function update(ArticleContentElement $articleContentElement, ArticleContentElementType $type, string|array $content): void;

    public function getFirstImage(Article $article): ArticleContentElement|null;
}
