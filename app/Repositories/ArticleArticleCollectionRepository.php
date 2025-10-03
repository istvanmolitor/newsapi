<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\ArticleArticleCollection;
use App\Models\ArticleCollection;

class ArticleArticleCollectionRepository implements ArticleArticleCollectionRepositoryInterface
{
    protected ArticleArticleCollection $model;

    public function __construct()
    {
        $this->model = new ArticleArticleCollection();
    }

    public function attach(Article $article, ArticleCollection $articleCollection): ArticleArticleCollection
    {
        return $this->model->firstOrCreate([
            'article_id' => $article->id,
            'article_collection_id' => $articleCollection->id,
        ]);
    }

    public function detach(Article $article, ArticleCollection $articleCollection): bool
    {
        return (bool) $this->model
            ->where('article_id', $article->id)
            ->where('article_collection_id', $articleCollection->id)
            ->delete();
    }
}
