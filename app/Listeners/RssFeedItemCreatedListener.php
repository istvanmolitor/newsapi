<?php

namespace App\Listeners;

use App\Services\ArticleService;
use Exception;
use Molitor\RssWatcher\Events\RssFeedItemCreatedEvent;

class RssFeedItemCreatedListener
{
    public function __construct(
        protected ArticleService $articleService,
    )
    {
    }

    public function handle(RssFeedItemCreatedEvent $event): void
    {
        dump("Rss feed item created: " . $event->item->guid);

        $url = $event->item->link;
        $article = $this->articleService->saveArticle($url, $event->item->title, $event->item->description, $event->item->image, $event->item->published_at);
        $this->articleService->dispatchScraping($article);
    }
}
