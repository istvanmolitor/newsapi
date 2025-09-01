<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\Portal;

interface ArticleRepositoryInterface
{
    public function find(Portal $portal, string $slug): ?Article;

    public function create(Portal $portal, string $slug): Article;

    public function getById(int $id): ?Article;
}
