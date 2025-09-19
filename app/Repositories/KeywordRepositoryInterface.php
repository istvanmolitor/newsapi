<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\Keyword;

interface KeywordRepositoryInterface
{
    public function create(string $keyword): Keyword;

    public function getById(int $id): ?Keyword;

    public function getByKeyword(string $keyword): ?Keyword;

    public function attachToArticle(Article $article, Keyword $keyword): void;
}
