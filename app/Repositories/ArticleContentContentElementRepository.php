<?php

namespace App\Repositories;

use App\Enums\ArticleContentElementType;
use App\Models\Article;
use App\Models\ArticleContentElement;

class ArticleContentContentElementRepository implements ArticleContentElementRepositoryInterface
{
    private ArticleContentElement $articleElement;

    public function __construct()
    {
        $this->articleElement = new ArticleContentElement();
    }

    public function create(
        Article $article,
        ArticleContentElementType $type,
        string|array $content
    ): ArticleContentElement
    {
        return $this->articleElement->create([
            'article_id' => $article->id,
            'type' => $type,
            'content' => $content,
        ]);
    }

    public function update(
        ArticleContentElement $articleContentElement,
        ArticleContentElementType $type,
        string|array $content
    ): void
    {
        $articleContentElement->fill([
            'type' => $type,
            'content' => $content,
        ]);
        $articleContentElement->save();
    }
}
