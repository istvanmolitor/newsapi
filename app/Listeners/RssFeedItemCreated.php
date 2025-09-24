<?php

namespace App\Listeners;

use Molitor\RssWatcher\Events\RssFeedItemCreated as Event;

class RssFeedItemCreated
{
    public function __construct()
    {
    }

    public function handle(Event $event): void
    {
        dump("Rss feed item created");
    }
}
