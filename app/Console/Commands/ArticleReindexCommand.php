<?php

namespace App\Console\Commands;

use App\Services\ElasticArticleService;
use Illuminate\Console\Command;

class ArticleReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex article';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /** @var ElasticArticleService $service */
        app(ElasticArticleService::class)->reindexAllArticles();
        $this->info('Article reindexed');
    }
}
