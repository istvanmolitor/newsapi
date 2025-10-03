<?php

namespace App\Providers;

use App\Repositories\ArticleContentElementRepository;
use App\Repositories\ArticleContentElementRepositoryInterface;
use App\Repositories\ArticleRepository;
use App\Repositories\ArticleRepositoryInterface;
use App\Repositories\PortalRepository;
use App\Repositories\PortalRepositoryInterface;
use App\Repositories\KeywordRepositoryInterface;
use App\Repositories\KeywordRepository;
use App\Repositories\ArticleCollectionRepositoryInterface;
use App\Repositories\ArticleCollectionRepository;
use App\Repositories\ArticleArticleCollectionRepositoryInterface;
use App\Repositories\ArticleArticleCollectionRepository;
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
        $this->app->bind(ArticleContentElementRepositoryInterface::class, ArticleContentElementRepository::class);
        $this->app->bind(KeywordRepositoryInterface::class, KeywordRepository::class);
        $this->app->bind(ArticleCollectionRepositoryInterface::class, ArticleCollectionRepository::class);
        $this->app->bind(ArticleArticleCollectionRepositoryInterface::class, ArticleArticleCollectionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
