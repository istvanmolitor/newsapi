<?php

namespace App\Services;

use App\Enums\ArticleContentElementType;
use App\Models\Portal;
use App\Repositories\PortalRepositoryInterface;
use App\Repositories\ArticleContentElementRepositoryInterface;
use App\Repositories\ArticleRepositoryInterface;

use Carbon\Carbon;
use Exception;
use Molitor\ArticleParser\Article\Article as ArticleObject;
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

use willvincent\Feeds\Facades\FeedsFacade;

class ArticleService
{
    private ?Portal $portal = null;

    private ?Article $article = null;

    public function __construct(
        private ArticleParserService $articleParserService,
        private PortalRepositoryInterface $portalRepository,
        private ArticleRepositoryInterface $articleRepository,
        private ArticleContentElementRepositoryInterface $articleContentElementRepository
    )
    {
    }

    private function urlToDomain(string $url): string
    {
        return parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);
    }

    public function setPortalByName(string $name): void
    {
        $portalModel = $this->portalRepository->getByName($name);
        if(!$portalModel) {
            throw new Exception('URL is required');
        }
        $this->portal = $portalModel;
    }

    public function setArticle(Article $article): void
    {
        $this->portal = $article->portal;
        $this->article = $article;
    }

    public function setArticleByUrl(string $url): void
    {
        $article = $this->articleRepository->getByUrl($url);
        if(!$article) {
            throw new Exception('The article is not found');
        }
        $this->setArticle($article);
    }

    protected function save(
        string $url,
        string $title,
        string $lead,
        ?string $createdAt
    ): void
    {
        $data = [
            'title' => $title,
            'lead' => $lead,
            'created_at' => $createdAt,
            'updated_at' => now(),
        ];

        try {
            $this->setArticleByUrl($url);
            $this->article->fill($data);
            $this->article->save();
        }
        catch (Exception $e) {
            if($this->portal === null) {
                throw new Exception('Portal is not set');
            }
            $this->article = $this->articleRepository->create($this->portal, $url, $data);
        }
    }

    public function saveMainImage(string $url, string $alt, string $author): void
    {
        if($this->article === null) {
            throw new Exception('Article is not set');
        }
        $this->article->fill([
            'main_image_src' => $url,
            'main_image_alt' => $alt,
            'main_image_author' => $author,
        ]);
        $this->article->save();
    }

    /**
     * @throws Exception
     */
    public function updateRss(string $name): void
    {
        $this->setPortalByName($name);

        $feed = FeedsFacade::make($this->portal->rss);

        if(!$feed) {
            throw new Exception('RSS feed is not valid');
        }

        foreach ($feed->get_items() as $item) {
            $this->save(
                $item->get_permalink(),
                $item->get_title(),
                (string)$item->get_description(),
                $item->get_date('Y-m-d H:i:s')
            );
        }
        $this->portal->rss_downloaded_at = now();
        $this->portal->save();
    }

    public function scrapeById(int $articleId): void
    {
        $article = $this->articleRepository->getById($articleId);
         if(!$article) {
             throw new Exception('Article is not found');
         }
         $this->setArticle($article);
         $this->scrapeArticle($article);
    }

    public function fillArticle () {
        if($this->article === null) {
            throw new Exception('Cannot fill article. Article is not set');
        }
        if(!$this->article->scraped_at) {
            $this->scrapeArticle();
            $this->article->scraped_at = now();
            $this->article->save();
        }
    }

    public function scrapeByUrl(string $url): void
    {
        try {
            $this->setArticleByUrl($url);
        }
        catch (Exception $e) {
            $this->save($url, '', '', '');
        }

        $this->scrapeArticle();
    }

    public function scrapeArticle(): bool
    {
        if($this->article === null) {
            throw new Exception('Cannot scrape article. Article is not set');
        }

        $articleObject = $this->articleParserService->getByUrl($this->article->url);

        if(!$articleObject) {
            throw new Exception('Canant scrape article. Remote article is not valid');
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
