<?php

namespace App\Repositories;

use App\Models\ArticleArticleCollection;

class ArticleArticleCollectionRepository implements ArticleArticleCollectionRepositoryInterface
{
    public function __construct(private ArticleArticleCollection $model = new ArticleArticleCollection())
    {
    }

    public function attach(int $articleId, int $collectionId): ArticleArticleCollection
    {
        return $this->model->firstOrCreate([
            'article_id' => $articleId,
            'article_collection_id' => $collectionId,
        ]);
    }

    public function detach(int $articleId, int $collectionId): bool
    {
        return (bool) $this->model
            ->where('article_id', $articleId)
            ->where('article_collection_id', $collectionId)
            ->delete();
    }
}
