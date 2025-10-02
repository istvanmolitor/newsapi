<?php

namespace App\Http\Controllers;

use App\Jobs\ScrapeArticleJob;
use App\Models\Article;
use App\Services\ArticleService;
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

    public function search(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $perPage = (int) $request->input('per_page', 12);
        $perPage = $perPage > 0 && $perPage <= 50 ? $perPage : 12;

        $articles = Article::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('title', 'like', "%{$q}%")
                        ->orWhere('lead', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('published_at')
            ->orderByDesc('title')
            ->paginate($perPage)
            ->withQueryString();

        return view('search.index', [
            'articles' => $articles,
            'q' => $q,
            'title' => $q !== '' ? "Keresés: {$q}" : 'Keresés',
        ]);
    }

    public function show(Article $article, Request $request)
    {
        if($article->scraped_at === null || $request->has('refresh')) {
            /** @var ArticleService $articleService */
            $articleService = app(ArticleService::class);
            $articleService->selectArticle($article);
            try {
                $articleService->scrapeArticle();
                $article = Article::find($article->id);
            }
            catch(Exception $e) {}
        }

        return view('article.show', [
            'article' => $article
        ]);
    }
}
