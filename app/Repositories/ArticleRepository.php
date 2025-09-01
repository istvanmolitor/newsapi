<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\Portal;

class ArticleRepository implements ArticleRepositoryInterface
{
    private Article $article;

    public function __construct()
    {
        $this->article = new Article();
    }

    public function find(Portal $portal, string $slug): ?Article
    {
        return $this->article->where('portal_id', $portal->id)->where('slug', $slug)->first();
    }

    public function create(Portal $portal, string $slug): Article
    {
        return $this->article->create([
            'portal_id' => $portal->id,
            'slug' => $slug,
            'original_url' => '',
            'title' => '',
            'author' => '',
            'main_image' => '',
            'lead' => '',
            'published_at' => null,
        ]);
    }

    public function getById(int $id): ?Article
    {
        return $this->article->find($id);
    }
}
