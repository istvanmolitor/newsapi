<?php

namespace App\Services;

use App\Enums\ArticleContentElementType;
use App\Models\Portal;
use App\Repositories\KeywordRepositoryInterface;
use App\Repositories\PortalRepositoryInterface;
use App\Repositories\ArticleContentElementRepositoryInterface;
use App\Repositories\ArticleRepositoryInterface;

use Exception;
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
    private ?Portal $portal = null;

    private ?Article $article = null;

    public function __construct(
        private ArticleParserService $articleParserService,
        private PortalRepositoryInterface $portalRepository,
        private ArticleRepositoryInterface $articleRepository,
        private ArticleContentElementRepositoryInterface $articleContentElementRepository,
        private KeywordRepositoryInterface $keywordRepository
    )
    {
    }

    private function urlToDomain(string $url): string
    {
        return parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);
    }

    public function selectPortalByName(string $name): void
    {
        $portalModel = $this->portalRepository->getByName($name);
        if(!$portalModel) {
            throw new Exception('No portal with name: ' . $name);
        }
        $this->portal = $portalModel;
    }

    public function selectPortalByUrl(string $url): void
    {
        $domain = $this->urlToDomain($url);
        $portalModel = $this->portalRepository->getByDomain($domain);
        if(!$portalModel) {
            throw new Exception('No portal found: ' . $domain);
        }
        $this->portal = $portalModel;
    }

    public function createPortalByUrl(string $url): void
    {
        $domain = $this->urlToDomain($url);
        $this->portal = $this->portalRepository->create(parse_url($url, PHP_URL_HOST), $domain);
    }

    public function selectArticle(Article $article): void
    {
        $this->portal = $article->portal;
        $this->article = $article;
    }

    public function selectArticleByUrl(string $url): void
    {
        $article = $this->articleRepository->getByUrl($url);
        if(!$article) {
            throw new Exception('The article url is not found');
        }
        $this->selectArticle($article);
    }

    public function save(
        string $url,
        string $title,
        string $lead,
        string $publishedAt
    ): void
    {
        $data = [
            'title' => $title,
            'lead' => $lead,
            'published_at' => $publishedAt,
        ];

        try {
            $this->selectArticleByUrl($url);
            $this->article->fill($data);
            $this->article->save();
        }
        catch (Exception $e) {
            if($this->portal === null) {
                throw new Exception('The portal is not selected.');
            }
            $this->article = $this->articleRepository->create($this->portal, $url, $data);
        }
    }

    public function saveMainImage(string $url, string $alt, string $author): void
    {
        if($this->article === null) {
            throw new Exception('The article is not selected.');
        }
        $this->article->fill([
            'main_image_src' => $url,
            'main_image_alt' => $alt,
            'main_image_author' => $author,
        ]);
        $this->article->save();
    }

    public function scrapeById(int $articleId): void
    {
        $article = $this->articleRepository->getById($articleId);
         if(!$article) {
             throw new Exception('Article is not found');
         }
         $this->selectArticle($article);
         $this->scrapeArticle();
    }

    public function fillArticle (): bool {
        if($this->article === null) {
            throw new Exception('Cannot fill article becouse the article is not selected.');
        }
        if($this->article->scraped_at) {
            return false;
        }
        $this->scrapeArticle();
        $this->article->scraped_at = now();
        $this->article->save();
        return true;
    }

    public function scrapeByUrl(string $url): void
    {
        $this->selectArticleByUrl($url);
        $this->scrapeArticle();
    }

    public function scrapeArticle(): bool
    {
        if($this->article === null) {
            throw new Exception('Cannot scrape article. Article is not selected');
        }

        $articleObject = $this->articleParserService->getByUrl($this->article->url);

        if(!$articleObject) {
            $this->article->scraped_at = now();
            $this->article->save();
            throw new Exception('Canant scrape article. Remote article is not valid. : ' . $this->article->url);
        }

        $this->article->fill([
            'title' => $articleObject->title,
            'lead' => $articleObject->lead,
            'author' => $articleObject->author,
            'main_image_src' => $articleObject->mainImage?->getSrc(),
            'main_image_alt' => $articleObject->mainImage?->getAlt(),
            'main_image_author' => $articleObject->mainImage?->getAuthor(),
            'scraped_at' => now(),
            'published_at' => $articleObject->createdAt,
            'updated_at' => now(),
        ]);
        $this->article->save();

        $this->keywordRepository->attachKeywordsToArticle($this->article, $articleObject->keywords);

        $articleContentElements = $this->article->articleContentElements;

        $oldCount = $articleObject->content->count();
        $newCount = $articleContentElements->count();
        $count = max($oldCount, $newCount);

        for($i = 0; $i < $count; $i++) {
            /** @var ArticleContentElementModel $articleContentElementModel */
            $articleContentElementModel = $articleContentElements->get($i);

            /** @var ArticleContentElement $articleContentElement */
            $articleContentElement = $articleObject->content->get($i);

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
                    $this->article,
                    $this->makeType($articleContentElement),
                    $this->makeContentString($articleContentElement)
                );
            }
            elseif ($articleContentElementModel !== null) {
                //DELETE
                $articleContentElementModel->delete();
            }
        }

        return true;
    }

    protected function makeType(ArticleContentElement $articleElement): ArticleContentElementType
    {
        if($articleElement instanceof ParagraphArticleContentElement) {
            return ArticleContentElementType::Paragraph;
        }
        elseif($articleElement instanceof QuoteArticleContentElement) {
            return ArticleContentElementType::Quote;
        }
        elseif($articleElement instanceof ImageArticleContentElement) {
            return ArticleContentElementType::Image;
        }
        elseif($articleElement instanceof ListArticleContentElement) {
            return ArticleContentElementType::List;
        }
        elseif($articleElement instanceof HeadingArticleContentElement) {
            return ArticleContentElementType::Heading;
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
        elseif($articleElement instanceof ImageArticleContentElement) {
            return json_encode($articleElement->getData());
        }
        elseif($articleElement instanceof ListArticleContentElement){
            return json_encode($articleElement->getItems());
        }
        elseif($articleElement instanceof HeadingArticleContentElement){
            return json_encode($articleElement->getData());
        }
        elseif($articleElement instanceof VideoArticleContentElement){
            return json_encode($articleElement->getData());
        }
        else {
            return '';
        }
    }
}
