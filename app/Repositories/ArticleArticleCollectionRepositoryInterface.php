<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\ArticleArticleCollection;
use App\Models\ArticleCollection;

interface ArticleArticleCollectionRepositoryInterface
{
    public function attach(Article $article, ArticleCollection $articleCollection): ArticleArticleCollection;

    public function detach(Article $article, ArticleCollection $articleCollection): bool;
}
