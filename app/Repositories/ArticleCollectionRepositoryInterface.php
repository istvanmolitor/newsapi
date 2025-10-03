<?php

namespace App\Repositories;

use App\Models\ArticleCollection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ArticleCollectionRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function all(): iterable;

    public function create(array $data): ArticleCollection;

    public function find(int $id): ?ArticleCollection;
}
