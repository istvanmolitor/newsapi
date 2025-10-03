<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\Portal;
use Illuminate\Support\LazyCollection;

interface ArticleRepositoryInterface
{
    public function create(Portal $portal, string $url, array $data): Article;

    public function getById(int $id): ?Article;

    public function getByUrl(string $url): ?Article;

    /**
     * Return articles that are not in any collection.
     */
    public function withoutCollections(): LazyCollection;
}
