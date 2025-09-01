<?php

namespace App\Services;

use App\Repositories\ArticleElementRepositoryInterface;
use App\Repositories\ArticleRepositoryInterface;
use App\Repositories\PortalRepositoryInterface;
use Molitor\ArticleParser\Services\ArticleParserService;

class ArticleService
{
    public function __construct(
        private ArticleParserService $articleParserService,
        private PortalRepositoryInterface $portalRepository,
        private ArticleRepositoryInterface $articleRepository,
        private ArticleElementRepositoryInterface $articleElementRepository,
    )
    {
    }

    public function update(string $url): bool
    {
        $article = $this->articleParserService->getByUrl($url);
        if(!$article) {
             return false;
        }

        $portalModel = $this->portalRepository->getByName($article->portal);
        if(!$portalModel) {
            $domain = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);
            $portalModel = $this->portalRepository->create($article->portal, $domain);
        }

        $articleModel = $this->articleRepository->getByUrl($url);

        $articleData = [
            'title' => $article->title,
            'lead' => $article->lead,
            'author' => $article->author,
            'main_image_src' => $article->mainImage?->src,
            'main_image_alt' => $article->mainImage?->alt,
            'main_image_author' => $article->mainImage?->author,
            'created_at' => $article->createdAt,
            'updated_at' => now(),
        ];

        if($articleModel) {
            $articleModel->fill($articleData);
            $articleModel->save();
        }
        else {
            $articleModel = $this->articleRepository->create($portalModel, $url, $articleData);
        }

        return true;
    }
}
