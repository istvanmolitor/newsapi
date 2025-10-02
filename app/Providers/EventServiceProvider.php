<?php

namespace App\Providers;

use App\Listeners\RssFeedItemChangedListener;
use App\Listeners\RssFeedItemCreatedListener;
use App\Listeners\RssFeedItemDeletedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Molitor\RssWatcher\Events\RssFeedItemChangedEvent;
use Molitor\RssWatcher\Events\RssFeedItemCreatedEvent;
use Molitor\RssWatcher\Events\RssFeedItemDeletedEvent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        RssFeedItemCreatedEvent::class => [
            RssFeedItemCreatedListener::class,
        ],
        RssFeedItemChangedEvent::class => [
            RssFeedItemChangedListener::class,
        ],
        RssFeedItemDeletedEvent::class => [
            RssFeedItemDeletedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }
}
