<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\ArticleCollection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

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

    public function create(string $title, string $lead, bool $isSame): ArticleCollection
    {
        return $this->model->create([
            'title' => $title,
            'lead' => $lead,
            'is_same' => $isSame,
        ]);
    }

    public function find(int $id): ?ArticleCollection
    {
        return $this->model->find($id);
    }

    public function delete(ArticleCollection $collection): bool
    {
        return $collection->delete();
    }

    public function getForHomepage(int $limit): Collection
    {
        return $this->model->limit($limit)->get();
    }

    public function getSameCollectionByArticle(Article $article): ?ArticleCollection
    {
        return $this->model->join('article_article_collection', 'article_article_collection.article_collection_id', '=', 'article_collections.id')
            ->where('article_article_collection.article_id', $article->id)
            ->where('article_collections.is_same', true)
            ->select('article_collections.*')
            ->first();
    }
}
