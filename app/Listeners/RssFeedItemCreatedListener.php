<?php

namespace App\Listeners;

use App\Services\ArticleService;
use Exception;
use Molitor\RssWatcher\Events\RssFeedItemCreatedEvent;

class RssFeedItemCreatedListener
{
    public function __construct()
    {
    }

    public function handle(RssFeedItemCreatedEvent $event): void
    {
        dump("Rss feed item created: " . $event->item->guid);
        /** @var ArticleService $articleService */
        $articleService = app(ArticleService::class);

        $url = $event->item->link;
        if(!$url) return;

        try {
            $articleService->selectPortalByUrl($url);
        }
        catch (Exception $e) {
            $articleService->createPortalByUrl($url);
        }

        $articleService->save($url, $event->item->title, $event->item->description, $event->item->image, $event->item->published_at);
    }
}
