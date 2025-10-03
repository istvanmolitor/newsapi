<?php

namespace App\Console\Commands;

use App\Services\ElasticService;
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
        /** @var ElasticService $service */
        app(ElasticService::class)->reindexAllArticles();
        $this->info('Article reindexed');
    }
}
