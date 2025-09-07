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

    protected function makeStringContent(ArticleContentElementType $type, string|array $content): string
    {
        return match ($type) {
            ArticleContentElementType::Paragraph, ArticleContentElementType::Quote => $content,
            default => json_encode($content),
        };
    }

    public function getContent(ArticleContentElement $articleContentElement): string|array
    {
        return match ($articleContentElement->type) {
            ArticleContentElementType::Paragraph, ArticleContentElementType::Quote => $articleContentElement->content,
            default => json_decode($articleContentElement->content),
        };
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
            'content' => $this->makeStringContent($type, $content),
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
            'content' => $this->makeStringContent($type, $content),
        ]);
        $articleContentElement->save();
    }
}
