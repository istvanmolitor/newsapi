<?php

namespace App\Services;

use App\Models\Article;
use Elasticsearch\ClientBuilder;

class ElasticService
{
    protected $index;

    protected $client;

    public function __construct()
    {
        $this->index = config('services.elastic.index');
        $this->client = ClientBuilder::create()
            ->setHosts([config('services.elastic.host')])
            ->build();
    }

    public function indexArticle(Article $article)
    {
        return $this->client->index([
            'index' => $this->index,
            'id'    => $article->id,
            'body'  => [
                'title'   => $article->title,
                'lead' => $article->lead,
            ],
        ]);
    }

    public function searchArticles(string $query, int $limit = 10)
    {
        $response = $this->client->search([
            'index' => $this->index,
            'body'  => [
                'size' => $limit,
                'query' => [
                    'multi_match' => [
                        'query'  => $query,
                        'fields' => ['title', 'lead'],
                    ]
                ]
            ]
        ]);

        $hits = $response['hits']['hits'] ?? [];

        // Elasticsearch találati sorrend (ID-k)
        $ids = array_map(fn($hit) => (int) $hit['_id'], $hits);

        if (empty($ids)) {
            return collect(); // üres collection
        }

        // Eloquent: lekérés ID szerint
        $articles = \App\Models\Article::whereIn('id', $ids)->get();

        // Sorrend visszaállítása az Elasticsearch találatok alapján
        $articles = $articles->sortBy(fn($article) => array_search($article->id, $ids))
            ->values();

        return $articles;
    }
}
