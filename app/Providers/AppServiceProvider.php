<?php

namespace App\Providers;

use App\Repositories\ArticleContentContentElementRepository;
use App\Repositories\ArticleContentElementRepositoryInterface;
use App\Repositories\ArticleRepository;
use App\Repositories\ArticleRepositoryInterface;
use App\Repositories\PortalRepository;
use App\Repositories\PortalRepositoryInterface;
use App\Repositories\KeywordRepositoryInterface;
use App\Repositories\KeywordRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PortalRepositoryInterface::class, PortalRepository::class);
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
        $this->app->bind(ArticleContentElementRepositoryInterface::class, ArticleContentContentElementRepository::class);
        $this->app->bind(KeywordRepositoryInterface::class, KeywordRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
