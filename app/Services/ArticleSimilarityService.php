<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleSimilarity;
use App\Repositories\ArticleRepositoryInterface;
use Illuminate\Support\Collection;

class ArticleSimilarityService
{
    protected ArticleSimilarity $articleSimilarity;

    public function __construct(
        protected ElasticArticleService      $elasticService,
        protected ArticleRepositoryInterface $articleRepository,
    )
    {
        $this->articleSimilarity = new ArticleSimilarity();
    }

    public function delete(Article $article, string $method)
    {
        $this->articleSimilarity
            ->where('article_id_1', $article->id)
            ->where('method', $method)
            ->delete();
    }

    public function calculate(Article $article, int $limit = 10): void
    {
        $method = 'elastic';
        $this->delete($article, $method);
        $response = $this->elasticService->search($article->title, $limit);
        $hits = $response['hits']['hits'] ?? [];

        $rows = [];
        foreach ($hits as $hit) {
            if($article->id != $hit['_id']) {
                $rows[] = [
                    'article_id_1' => $article->id,
                    'article_id_2' => $hit['_id'],
                    'similarity' => $hit['_score'],
                    'method' => $method,
                    'computed_at' => now(),
                ];
            }
        }
        $this->articleSimilarity->insert($rows);
    }

    public function calculateAll(): void
    {
        $this->articleSimilarity->truncate();
        $articles = $this->articleRepository->withoutCollections();
        foreach ($articles as $article) {
            $this->calculate($article);
        }
    }

    public function getSimilarArticles(Article $article, int $limit): Collection
    {
        $similarities = $this->articleSimilarity
            ->where('article_id_1', $article->id)
            ->orderBy('similarity', 'desc')
            ->limit($limit)
            ->get();

        $articleIds = $similarities->pluck('article_id_2')->toArray();

        if (empty($articleIds)) {
            return new Collection();
        }

        $articles = Article::whereIn('id', $articleIds)->get();

        $articlesOrdered = collect($articleIds)->map(function ($id) use ($articles) {
            return $articles->firstWhere('id', $id);
        })->filter();

        return $articlesOrdered;
    }

    public function truncate(): void
    {
        $this->articleSimilarity->truncate();
    }
}
