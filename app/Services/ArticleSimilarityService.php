<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleSimilarity;

class ArticleSimilarityService
{
    protected ElasticService $elasticService;

    protected ArticleSimilarity $articleSimilarity;

    public function __construct()
    {
        $this->elasticService = new ElasticService();

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
        Article::chunk(100, function ($articles) {
            foreach ($articles as $article) {
                $this->calculate($article);
            }
        });
    }
}
