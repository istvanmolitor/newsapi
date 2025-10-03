<?php

namespace App\Console\Commands;

use App\Services\ArticleService;
use App\Services\ElasticArticleService;
use Exception;
use Illuminate\Console\Command;

class ArticleScrapeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:scrape {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape article by id';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');

        /** @var ArticleService $articleService */
        $articleService = app(ArticleService::class);
        $elasticService = app(ElasticArticleService::class);

        try {
            $article = $articleService->scrapeById($id);
            $elasticService->indexArticle($article);
            $this->info('Article is scraped. : ' . $article->title);
        }
        catch(Exception $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }
}
