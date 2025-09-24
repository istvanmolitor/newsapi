<?php

namespace App\Listeners;

use Molitor\RssWatcher\Events\RssFeedItemChanged as Event;

class RssFeedItemChanged
{
    public function __construct()
    {
    }

    public function handle(Event $event): void
    {
        dump("Rss feed item changed");
    }
}
