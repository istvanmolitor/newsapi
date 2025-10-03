<?php

namespace App\Repositories;

use App\Models\ArticleArticleCollection;

interface ArticleArticleCollectionRepositoryInterface
{
    public function attach(int $articleId, int $collectionId): ArticleArticleCollection;

    public function detach(int $articleId, int $collectionId): bool;
}
