<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\ArticleElement;

class ArticleElementRepository implements ArticleElementRepositoryInterface
{
    private ArticleElement $articleElement;

    public function __construct()
    {
        $this->articleElement = new ArticleElement();
    }

    public function create(Article $article, string $type, string $content): ArticleElement
    {
        return $this->articleElement->create([
            'article_id' => $article->id,
            'type' => $type,
            'content' => $content,
        ]);
    }
}
