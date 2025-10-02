<?php

namespace App\Listeners;

use Molitor\RssWatcher\Events\RssFeedItemDeletedEvent;

class RssFeedItemDeletedListener
{
    public function __construct()
    {
    }

    public function handle(RssFeedItemDeletedEvent $event): void
    {
        dump("Rss feed item deleted: ". $event->item->guid);
    }
}
