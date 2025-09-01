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
}
