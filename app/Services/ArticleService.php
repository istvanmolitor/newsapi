<?php

namespace App\Services;

use App\Enums\ArticleContentElementType;
use App\Jobs\ScrapeArticleJob;
use App\Models\Portal;
use App\Repositories\KeywordRepositoryInterface;
use App\Repositories\PortalRepositoryInterface;
use App\Repositories\ArticleContentElementRepositoryInterface;
use App\Repositories\ArticleRepositoryInterface;

use Exception;
use Molitor\ArticleParser\Article\Article as ArticleData;
use Molitor\ArticleParser\Article\ArticleContentElement;

use App\Models\Article;
use App\Models\ArticleContentElement as ArticleContentElementModel;

use Molitor\ArticleParser\Article\ArticleContentImage;
use Molitor\ArticleParser\Article\ArticleContentParagraph;
use Molitor\ArticleParser\Article\HeadingArticleContentElement;
use Molitor\ArticleParser\Article\ImageArticleContentElement;
use Molitor\ArticleParser\Article\ListArticleContentElement;
use Molitor\ArticleParser\Article\ParagraphArticleContentElement;
use Molitor\ArticleParser\Article\QuoteArticleContentElement;
use Molitor\ArticleParser\Article\QuoteContentElement;
use Molitor\ArticleParser\Article\VideoArticleContentElement;
use Molitor\ArticleParser\Services\ArticleParserService;

class ArticleService
{
    public function __construct(
        private ArticleParserService $articleParserService,
        private PortalRepositoryInterface $portalRepository,
        private ArticleRepositoryInterface $articleRepository,
        private ArticleContentElementRepositoryInterface $articleContentElementRepository,
        private KeywordRepositoryInterface $keywordRepository,
    )
    {
    }

    private function urlToDomain(string $url): string|null
    {
        $scheme = parse_url($url, PHP_URL_SCHEME);
        $host = parse_url($url, PHP_URL_HOST);
        if(!$scheme || !$host) {
            return null;
        }
        return $scheme . '://' . $host;
    }

    public function getArticleById(int $articleId): Article|null
    {
        return $this->articleRepository->getById($articleId);
    }

    public function getPortalByName(string $name): Portal|null
    {
        return $this->portalRepository->getByName($name);
    }

    public function getPortalByUrl(string $url): Portal|null
    {
        $domain = $this->urlToDomain($url);
        if(!$domain) {
            return null;
        }
        return $this->portalRepository->getByDomain($domain);
    }

    public function createPortal(string $name, string $domain): Portal
    {
        return $this->portalRepository->create($name, $domain);
    }

    public function createPortalByUrl(string $url): Portal|null
    {
        $domain = $this->urlToDomain($url);
        if(!$domain) {
            return null;
        }
        return $this->portalRepository->create(parse_url($url, PHP_URL_HOST), $domain);
    }

    public function getArticleByUrl(string $url): Article|null
    {
        return $this->articleRepository->getByUrl($url);
    }

    public function updateListImage(Article $article): bool
    {
        $mainImageSrc = $article->main_image_src;
        if($mainImageSrc) {
            $article->list_image_src = $mainImageSrc;
            if($article->save()) {
                return true;
            }
        }

        $firstImage = $this->articleContentElementRepository->getFirstImage($article);
        if($firstImage) {
            $content = $firstImage->getContent();
            if(isset($content['src'])) {
                $article->list_image_src = $content['src'];
                if($article->save()) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function makeArticle(string $url): Article|null
    {
        $portal = $this->getPortalByUrl($url);
        if(!$portal) {
            $portal = $this->createPortalByUrl($url);
            if(!$portal) {
                return null;
            }
        }
        return new Article([
            'portal_id' => $portal->id,
            'url' => $url,
            'hash' => md5($url),
        ]);
    }

    public function saveArticle(
        string $url,
        string $title,
        string $lead,
        string|null $image,
        string $publishedAt
    ): Article|null
    {
        $article = $this->getArticleByUrl($url);
        if(!$article) {
            $article = $this->makeArticle($url);
        }

        $article->fill([
            'title' => $title,
            'lead' => $lead,
            'main_image_src' => $image,
            'published_at' => $publishedAt,
        ]);
        if(!$article->save()) {
            return null;
        }

        $this->updateListImage($article);

        return $article;
    }

    public function createArticle(
        string $url,
        string $title,
        string $lead,
        string|null $image,
        string $publishedAt
    )
    {
        $portal = $this->getPortalByUrl($url);
        if(!$portal) {
            $portal = $this->createPortal('Unknown', $this->urlToDomain($url));
        }

        $article = $this->articleRepository->create($portal, $url, [
            'title' => $title,
            'lead' => $lead,
            'main_image_src' => $image,
            'published_at' => $publishedAt,
        ]);

        $this->updateListImage($article);

        return $article;
    }

    public function dispatchScraping(Article $article): bool
    {
        if($article->scraped_at === null) {
            ScrapeArticleJob::dispatch($article->id);
            return true;
        }
        return false;
    }

    public function scrapeById(int $articleId): Article|null
    {
        $article = $this->getArticleById($articleId);
        if(!$article) {
            return null;
        }
        $this->scrapeArticle($article);
        return $article;
    }

    public function scrapeByUrl(string $url): Article|null
    {
        $particleData = $this->getArticleDataByUrl($url);
        if(!$particleData) {
            return null;
        }

        $article = $this->getArticleByUrl($url);
        if(!$article) {
            $article = $this->makeArticle($url);
        }

        if($this->saveArticleData($article, $particleData)) {
            return $article;
        }
        return null;
    }

    public function getArticleDataByUrl(string $url): ArticleData|null
    {
        return $this->articleParserService->getByUrl($url);
    }

    public function scrapeArticle(Article $article): bool
    {
        $url = $article->url;
        if(empty($url)) {
            return false;
        }

        $articleData = $this->getArticleDataByUrl($url);
        if(!$articleData) {
            return false;
        }

        return $this->saveArticleData($article, $articleData);
    }

    protected function saveArticleData(Article $article, ArticleData $articleData): bool
    {
        $mainImage = $articleData->getMainImage();

        $article->fill([
            'hash' => md5($article->url),
            'title' => $articleData->getTitle(),
            'lead' => $articleData->getLead(),
            'author' => implode(',', $articleData->getAuthors()),
            'main_image_src' => $mainImage?->getSrc(),
            'main_image_alt' => $mainImage?->getAlt(),
            'main_image_author' => $mainImage?->getAuthor(),
            'scraped_at' => now(),
            'published_at' => $articleData->getCreatedAt(),
            'updated_at' => now(),
        ]);
        if(!$article->save())
        {
            return false;
        }

        $this->keywordRepository->attachKeywordsToArticle($article, $articleData->getKeywords());

        $articleContentElements = $article->articleContentElements;

        $content = $articleData->getContent();

        $oldCount = $content->count();
        $newCount = $articleContentElements->count();
        $count = max($oldCount, $newCount);

        for($i = 0; $i < $count; $i++) {
            /** @var ArticleContentElementModel $articleContentElementModel */
            $articleContentElementModel = $articleContentElements->get($i);

            /** @var ArticleContentElement $articleContentElement */
            $articleContentElement = $content->get($i);

            if($articleContentElement !== null && $articleContentElementModel !== null) {
                //UPDATE
                $this->articleContentElementRepository->update(
                    $articleContentElementModel,
                    $this->makeType($articleContentElement),
                    $this->makeContentString($articleContentElement)
                );
            }
            elseif ($articleContentElement !== null) {
                //CREATE
                $this->articleContentElementRepository->create(
                    $article,
                    $this->makeType($articleContentElement),
                    $this->makeContentString($articleContentElement)
                );
            }
            elseif ($articleContentElementModel !== null) {
                //DELETE
                $articleContentElementModel->delete();
            }
        }
        $this->updateListImage($article);
        return true;
    }

    protected function makeType(ArticleContentElement $articleElement): ArticleContentElementType
    {
        if($articleElement instanceof QuoteArticleContentElement) {
            return ArticleContentElementType::Quote;
        }
        elseif($articleElement instanceof HeadingArticleContentElement) {
            return ArticleContentElementType::Heading;
        }
        elseif($articleElement instanceof ParagraphArticleContentElement) {
            return ArticleContentElementType::Paragraph;
        }
        elseif($articleElement instanceof ImageArticleContentElement) {
            return ArticleContentElementType::Image;
        }
        elseif($articleElement instanceof ListArticleContentElement) {
            return ArticleContentElementType::List;
        }
        elseif($articleElement instanceof VideoArticleContentElement) {
            return ArticleContentElementType::Video;
        }
        else {
            throw new Exception('Unknown article content element type. ' . get_class($articleElement));
        }
    }

    protected function makeContentString(ArticleContentElement $articleElement): string
    {
        if($articleElement instanceof ParagraphArticleContentElement) {
            return $articleElement->getContent();
        }
        elseif($articleElement instanceof QuoteArticleContentElement) {
            return $articleElement->getContent();
        }
        elseif($articleElement instanceof HeadingArticleContentElement){
            return $articleElement->getContent();
        }
        elseif($articleElement instanceof ImageArticleContentElement) {
            return json_encode($articleElement->getData());
        }
        elseif($articleElement instanceof ListArticleContentElement){
            return json_encode($articleElement->getItems());
        }
        elseif($articleElement instanceof VideoArticleContentElement){
            return json_encode($articleElement->getData());
        }
        else {
            return '';
        }
    }
}
