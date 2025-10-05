<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\Portal;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

class ArticleRepository implements ArticleRepositoryInterface
{
    private Article $article;

    public function __construct()
    {
        $this->article = new Article();
    }

    public function create(Portal $portal, string $url, array $data): Article
    {
        $row = array_merge([
            'portal_id' => $portal->id,
            'url' => $url,
            'hash' => md5($url),
        ], $data);
        return $this->article->create($row);
    }

    public function getById(int $id): ?Article
    {
        return $this->article->find($id);
    }

    public function getByUrl(string $url): ?Article
    {
        return $this->article->where('hash', md5($url))->where('url', $url)->first();
    }

    public function articlesInSameCollections(Article $article, int $limit = 10): Collection
    {
        $collectionIds = $article->collections()->pluck('article_collections.id');
        if ($collectionIds->isEmpty()) {
            return collect();
        }

        return $this->article->where('id', '!=', $article->id)
            ->whereHas('collections', function ($q) use ($collectionIds) {
                $q->whereIn('article_collections.id', $collectionIds);
            })
            ->with('portal')
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    public function withoutCollections(): LazyCollection
    {
        return $this->article
            ->whereDoesntHave('collections')
            ->orderByDesc('published_at')
            ->cursor();
    }

    public function getRecommendedArticles(Article $article, int $limit): Collection
    {
        return $this->article
            ->where('id', '!=', $article->id)
            ->whereNotNull('published_at')
            ->inRandomOrder()
            ->with('portal')
            ->limit($limit)
            ->get();
    }
}
