<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\Keyword;
use Illuminate\Database\Eloquent\Collection;

interface KeywordRepositoryInterface
{
    public function create(string $keyword): Keyword;

    public function getById(int $id): ?Keyword;

    public function getByKeyword(string $keyword): ?Keyword;

    public function attachToArticle(Article $article, Keyword $keyword): void;

    /**
     * Attach multiple string keywords to an article (creates keywords if missing).
     *
     * @param Article $article
     * @param string[] $keywords
     */
    public function attachKeywordsToArticle(Article $article, array $keywords): void;

    public function search(string $q, int $int): Collection;
}
