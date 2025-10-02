<?php

namespace App\Listeners;

use Molitor\RssWatcher\Events\RssFeedItemChangedEvent;

class RssFeedItemChangedListener
{
    public function __construct()
    {
    }

    public function handle(RssFeedItemChangedEvent $event): void
    {
        dump("Rss feed item changed: " . $event->item->guid);
    }
}
