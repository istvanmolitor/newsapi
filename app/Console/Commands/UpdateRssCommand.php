<?php

namespace App\Console\Commands;

use App\Services\ArticleService;
use Exception;
use Illuminate\Console\Command;

class UpdateRssCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rss:update {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update RSS feed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        /** @var ArticleService $service */
        $service = app(ArticleService::class);

        try {
            $service->updateRss($name);
        }
        catch (Exception $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }
}
