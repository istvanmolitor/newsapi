<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Services\ArticleSimilarityService;
use App\Services\ElasticArticleService;
use Illuminate\Console\Command;

class SimilarityCalculateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'similarity:calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate similarity for all articles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /** @var ArticleSimilarityService $service */
        $service = app(ArticleSimilarityService::class);
        $service->truncate();
        $service->calculateAll();
    }
}
