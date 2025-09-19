<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\Keyword;

class KeywordRepository implements KeywordRepositoryInterface
{
    private Keyword $keyword;

    public function __construct()
    {
        $this->keyword = new Keyword();
    }

    public function create(string $keyword): Keyword
    {
        return $this->keyword->create([
            'keyword' => $keyword,
        ]);
    }

    public function getById(int $id): ?Keyword
    {
        return $this->keyword->find($id);
    }

    public function getByKeyword(string $keyword): ?Keyword
    {
        return $this->keyword->where('keyword', $keyword)->first();
    }

    public function attachToArticle(Article $article, Keyword $keyword): void
    {
        $article->keywords()->syncWithoutDetaching([$keyword->id]);
    }
}
