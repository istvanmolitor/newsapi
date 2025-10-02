<?php

namespace App\Jobs;

use App\Services\ArticleService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ScrapeArticleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $articleId;

    public function __construct(int $articleId)
    {
        $this->articleId = $articleId;
        // Optionally set queue name
        $this->onQueue('scraping');
    }

    public function handle(): void
    {
        /** @var ArticleService $articleService */
        $articleService = app(ArticleService::class);

        try {
            $articleService->scrapeById($this->articleId);
        }
        catch (Exception $e) {
            Log::warning('ScrapeArticleJob failed for article '.$this->articleId.': '.$e->getMessage());
            // rethrow to allow retry handling
            throw $e;
        }
    }
}
