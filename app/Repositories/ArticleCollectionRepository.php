<?php

namespace App\Repositories;

use App\Models\ArticleCollection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticleCollectionRepository implements ArticleCollectionRepositoryInterface
{
    public function __construct(private ArticleCollection $model = new ArticleCollection())
    {
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->withCount('articles')->orderByDesc('created_at')->paginate($perPage);
    }

    public function all(): iterable
    {
        return $this->model->orderByDesc('created_at')->get();
    }

    public function create(array $data): ArticleCollection
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?ArticleCollection
    {
        return $this->model->find($id);
    }
}
