<?php

namespace App\Providers;

use App\Listeners\RssFeedItemChanged;
use App\Listeners\RssFeedItemCreated;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        \Molitor\RssWatcher\Events\RssFeedItemCreated::class => [
            RssFeedItemCreated::class,
        ],
        \Molitor\RssWatcher\Events\RssFeedItemChanged::class => [
            RssFeedItemChanged::class,
        ],
        \Molitor\RssWatcher\Events\RssFeedItemDeleted::class => [
            RssFeedItemDeleted::class,
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
