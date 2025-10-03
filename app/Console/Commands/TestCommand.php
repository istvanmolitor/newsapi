<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Services\ArticleSimilarityService;
use App\Services\ElasticService;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teszt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Teszt';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /** @var ArticleSimilarityService $service */
        $service = app(ArticleSimilarityService::class);

        $service->calculateAll();
    }
}
