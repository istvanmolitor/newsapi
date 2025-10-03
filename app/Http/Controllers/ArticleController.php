<?php

namespace App\Http\Controllers;

use App\Jobs\ScrapeArticleJob;
use App\Models\Article;
use App\Repositories\ArticleRepository;
use App\Services\ArticleService;
use App\Services\ElasticService;
use Exception;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 12);
        $perPage = $perPage > 0 && $perPage <= 50 ? $perPage : 12;

        $articles = Article::orderByDesc('published_at')
            ->orderByDesc('title')
            ->paginate($perPage)
            ->withQueryString();

        return view('article.index', [
            'articles' => $articles,
            'title' => 'Hírek',
        ]);
    }

    public function search(Request $request, ElasticService $elasticService)
    {
        $q = trim((string) $request->input('q', ''));

        $articles = $elasticService->searchArticles($q);

        return view('search.index', [
            'articles' => $articles,
            'q' => $q,
            'title' => $q !== '' ? "Keresés: {$q}" : 'Keresés',
        ]);
    }

    public function scrape(Article $article)
    {
        $articleService = app(ArticleService::class);
        $articleService->selectArticle($article);
        try {
            $articleService->scrapeArticle();
            $article = Article::find($article->id);
        }
        catch(Exception $e) {}
        return redirect()->route('article.show', $article);
    }

    public function show(Article $article, Request $request, ArticleRepository $articleRepository)
    {
        if($article->scraped_at === null) {
            return redirect()->route('article.scrape', $article);
        }

        // Eager-load keywords with articles_count and sort them by count desc
        $article->load(['keywords' => function($q) {
            $q->withCount('articles');
        }]);
        // Sort in-memory to preserve descending order by articles_count, then by keyword as tiebreaker
        $article->setRelation('keywords', $article->keywords->sortByDesc('articles_count')->values());

        $recommendedArticles = $articleRepository->articlesInSameCollections($article, 3);

        return view('article.show', [
            'article' => $article,
            'recommendedArticles' => $recommendedArticles,
        ]);
    }
}
