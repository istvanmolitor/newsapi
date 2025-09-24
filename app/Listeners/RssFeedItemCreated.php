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

        $url = $event->item->url;

        try {
            $articleService->setArticleByUrl($url);
        }
        catch (Exception $e) {
            $articleService->createPortal();
        }
    }
}
