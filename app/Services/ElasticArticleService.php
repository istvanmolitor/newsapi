<?php

namespace App\Services;

use App\Enums\ArticleContentElementType;
use App\Models\Article;
use Elasticsearch\ClientBuilder;

class ElasticArticleService
{
    protected $index;

    protected $client;

    public function __construct()
    {
        $this->index = config('services.elastic.index');
        $this->client = ClientBuilder::create()
            ->setHosts([config('services.elastic.host')])
            ->build();

        if (!$this->indexExists()) {
            $this->createIndex();
        }
    }

    public function createIndex(): void
    {
        $this->client->indices()->create([
            'index' => $this->index,
            'body' => [
                'settings' => [
                    'analysis' => [
                        'analyzer' => [
                            'hungarian_analyzer' => [
                                'tokenizer' => 'standard',
                                'filter' => [
                                    'lowercase',
                                    'hungarian_stop',
                                    'hungarian_stemmer'
                                ]
                            ]
                        ],
                        'filter' => [
                            'hungarian_stop' => [
                                'type' => 'stop',
                                'stopwords' => '_hungarian_'
                            ],
                            'hungarian_stemmer' => [
                                'type' => 'stemmer',
                                'language' => 'hungarian'
                            ]
                        ]
                    ]
                ],
                'mappings' => [
                    'properties' => [
                        'title' => [
                            'type' => 'text',
                            'analyzer' => 'hungarian_analyzer'
                        ],
                        'lead' => [
                            'type' => 'text',
                            'analyzer' => 'hungarian_analyzer'
                        ],
                        'content' => [
                            'type' => 'text',
                            'analyzer' => 'hungarian_analyzer'
                        ],
                    ]
                ]
            ]
        ]);
    }

    public function indexExists(): bool
    {
        return $this->client->indices()->exists([
            'index' => $this->index
        ]);
    }

    public function getContentByArticle(Article $article): string
    {
        $types = [
            ArticleContentElementType::Paragraph,
            ArticleContentElementType::Quote,
            ArticleContentElementType::Heading
        ];

        $content = [];
        foreach ($article->articleContentElements as $articleContentElement) {
            $type = $articleContentElement->type;
            if(in_array($type, $types)) {
                $content[] = $articleContentElement->content;
            }
        }
        return implode(' ', $content);
    }

    public function indexArticle(Article $article): array
    {
        return $this->client->index([
            'index' => $this->index,
            'id'    => $article->id,
            'body'  => [
                'title'   => $article->title,
                'lead' => $article->lead,
                'content' => $this->getContentByArticle($article),
            ],
        ]);
    }

    public function search(string $query, int $limit = 10): array
    {
        return $this->client->search([
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
    }

    public function searchArticles(string $query, int $limit = 10)
    {
        $response = $this->search($query, $limit);

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

    public function deleteIndex(): void
    {
        $this->client->indices()->delete([
            'index' => $this->index
        ]);
    }

    public function indexAllArticles(): int
    {
        $indexed = 0;

        Article::chunk(100, function ($articles) use (&$indexed) {
            foreach ($articles as $article) {
                $this->indexArticle($article);
                $indexed++;
            }
        });

        return $indexed;
    }

    public function reindexAllArticles(): int
    {
        if($this->indexExists()) {
            $this->deleteIndex();
        }
        $this->createIndex();
        return $this->indexAllArticles();
    }

}
