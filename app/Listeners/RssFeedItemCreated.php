<?php

namespace App\Listeners;

use App\Services\ArticleService;
use Exception;
use Molitor\RssWatcher\Events\RssFeedItemCreated as Event;

class RssFeedItemCreated
{
    public function __construct()
    {
    }

    public function handle(Event $event): void
    {
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

        $articleService->save($url, $event->item->title, $event->item->description, $event->item->published_at);
    }
}
