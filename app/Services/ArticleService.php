<?php

namespace App\Services;

use App\Enums\ArticleContentElementType;
use App\Repositories\PortalRepositoryInterface;
use App\Repositories\ArticleContentElementRepositoryInterface;
use App\Repositories\ArticleRepositoryInterface;

use Molitor\ArticleParser\Article\Article;
use Molitor\ArticleParser\Article\ArticleContentElement;

use App\Models\Article as ArticleModel;
use App\Models\ArticleContentElement as ArticleContentElementModel;

use Molitor\ArticleParser\Article\ArticleContentParagraph;
use Molitor\ArticleParser\Article\QuoteContentElement;
use Molitor\ArticleParser\Services\ArticleParserService;

class ArticleService
{
    public function __construct(
        private ArticleParserService $articleParserService,
        private PortalRepositoryInterface $portalRepository,
        private ArticleRepositoryInterface $articleRepository,
        private ArticleContentElementRepositoryInterface $articleContentElementRepository
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

        /** @var ArticleModel $articleModel */
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

        $articleContentElements = $articleModel->articleContentElements;

        $oldCount = $article->content->count();
        $newCount = $articleContentElements->count();
        $count = max($oldCount, $newCount);

        for($i = 0; $i < $count; $i++) {
            /** @var ArticleContentElementModel $articleContentElementModel */
            $articleContentElementModel = $articleContentElements->get($i);

            /** @var ArticleContentElement $articleContentElement */
            $articleContentElement = $article->content->get($i);

            if($articleContentElement !== null && $articleContentElementModel !== null) {
                //UPDATE
                $this->articleContentElementRepository->update(
                    $articleContentElementModel,
                    $this->makeType($articleContentElement),
                    $this->makeContent($articleContentElement)
                );
            }
            elseif ($articleContentElement !== null) {
                //CREATE
                $this->articleContentElementRepository->create(
                    $articleModel,
                    $this->makeType($articleContentElement),
                    $this->makeContent($articleContentElement)
                );
            }
            elseif ($articleContentElementModel !== null) {
                //DELETE
                $articleContentElementModel->delete();
            }
        }

        return true;
    }

    protected function save(Article $article)
    {

    }

    protected function makeType(ArticleContentElement $articleElement): ArticleContentElementType
    {
        return match ($articleElement->getType()) {
            'paragraph' => ArticleContentElementType::Paragraph,
            'quote' => ArticleContentElementType::Quote,
            default => ''
        };
    }

    protected function makeContent(ArticleContentElement $articleElement): string
    {
        if($articleElement instanceof ArticleContentParagraph) {
            return $articleElement->getContent()['content'] ?? '';
        }
        elseif ($articleElement instanceof QuoteContentElement) {
            return $articleElement->getContent()['content'] ?? '';
        }
        else {
            return json_encode($articleElement->getContent());
        }
    }
}
