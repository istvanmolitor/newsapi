<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleCollection;
use App\Models\ArticleSimilarity;
use App\Repositories\ArticleArticleCollectionRepositoryInterface;
use App\Repositories\ArticleCollectionRepositoryInterface;

class ArticleCollectionService
{
    public function __construct(
        protected ArticleCollectionRepositoryInterface $collectionRepository,
        protected ArticleArticleCollectionRepositoryInterface $articleArticleCollectionRepository
    )
    {
    }

    public function joinSimilarArticles(ArticleSimilarity $articleSimilarity): ArticleCollection
    {
        return $this->setSameArticle($articleSimilarity->article1, $articleSimilarity->article2);
    }

    public function setSameArticle(Article $article1, Article $article2): ArticleCollection
    {
        $collection1 = $this->getCollectionWithSameArticle($article1);
        $collection2 = $this->getCollectionWithSameArticle($article2);

        if($collection1 && $collection2) {
            return $this->mergeCollections($collection1, $collection2);
        }
        elseif ($collection1) {
            $this->addArticleToCollection($collection1, $article2);
            return $collection1;
        }
        elseif ($collection2) {
            $this->addArticleToCollection($collection2, $article1);
            return $collection2;
        }
        else {
            $collection = $this->createCollectionByArticle($article1, true);
            $this->addArticleToCollection($collection, $article2);
            return $collection;
        }
    }

    public function getCollectionWithSameArticle(Article $article): ?ArticleCollection
    {
        return ArticleCollection::join('article_article_collection', 'article_article_collection.article_collection_id', '=', 'article_collections.id')
            ->where('article_article_collection.article_id', '=', $article->id)
            ->where('article_collections.is_same', '=', true)
            ->select('article_collections.*')
            ->first();
    }

    public function addArticleToCollection(ArticleCollection $collection, Article $article): void
    {
        $this->articleArticleCollectionRepository->attach($article, $collection);
    }

    public function mergeCollections(ArticleCollection $collection1, ArticleCollection $collection2): ArticleCollection
    {
        foreach ($collection2->articles as $article) {
            $this->addArticleToCollection($collection1, $article);
            $this->articleArticleCollectionRepository->detach($article, $collection2);
        }
        $this->collectionRepository->delete($collection2);
        $collection2->delete();
        return $collection1;
    }

    public function createCollectionByArticle(Article $article, bool $isSame = false): ArticleCollection
    {
        $articleCollection = $this->collectionRepository->create($article->title, $article->lead, $isSame);
        $this->addArticleToCollection($articleCollection, $article);
        return $articleCollection;
    }
}
