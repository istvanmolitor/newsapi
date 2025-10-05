<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Repositories\ArticleCollectionRepositoryInterface;
use App\Repositories\ArticleRepository;
use App\Services\ArticleService;
use App\Services\ArticleSimilarityService;
use App\Services\ElasticArticleService;
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

    public function scrape(Article $article, ArticleService $articleService, ElasticArticleService $elasticArticleService)
    {
        $articleService->scrapeArticle($article);
        $elasticArticleService->indexArticle($article);

        return redirect()->route('article.show', $article);
    }

    public function show(Article $article, Request $request, ArticleRepository $articleRepository, ArticleCollectionRepositoryInterface $articleCollectionRepository)
    {
        $article->load(['keywords' => function($q) {
            $q->withCount('articles');
        }]);

        $article->setRelation('keywords', $article->keywords->sortByDesc('articles_count')->values());

        $recommendedArticles = $articleRepository->getRecommendedArticles($article, 3);

        return view('article.show', [
            'article' => $article,
            'recommendedArticles' => $recommendedArticles,
        ]);
    }
}
