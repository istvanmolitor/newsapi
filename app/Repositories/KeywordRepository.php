<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\Keyword;
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * Attach multiple string keywords to an article (creates keywords if missing).
     *
     * @param Article $article
     * @param string[] $keywords
     */
    public function attachKeywordsToArticle(Article $article, array $keywords): void
    {
        // Normalize: trim, remove empties, lowercase (optional based on domain), unique
        $normalized = [];
        foreach ($keywords as $kw) {
            if (!is_string($kw)) {
                continue;
            }
            $k = trim($kw);
            if ($k === '') {
                continue;
            }
            $normalized[] = $k;
        }
        if (empty($normalized)) {
            return;
        }
        $normalized = array_values(array_unique($normalized));

        $ids = [];
        foreach ($normalized as $kw) {
            $existing = $this->getByKeyword($kw);
            if (!$existing) {
                $existing = $this->create($kw);
            }
            $ids[] = $existing->id;
        }

        if (!empty($ids)) {
            $article->keywords()->syncWithoutDetaching($ids);
        }
    }

    public function search(string $q, int $int): Collection
    {
        return $this->keyword
            ->where('keyword', 'like', '%' . $q . '%')
            ->orderBy('keyword')
            ->limit($int)
            ->get();
    }
}
