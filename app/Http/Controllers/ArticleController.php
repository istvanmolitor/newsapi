<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 12);
        $perPage = $perPage > 0 && $perPage <= 50 ? $perPage : 12;

        $articles = Article::orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        return view('article.index', [
            'articles' => $articles,
            'title' => 'Hírek',
        ]);
    }

    public function show(Article $article, Request $request)
    {
        if($article->scraped_at === null || $request->has('refresh')) {
            /** @var ArticleService $articleService */
            $articleService = app(ArticleService::class);
            $articleService->setArticle($article);
            $articleService->scrapeArticle();
            $article = Article::find($article->id);
        }

        return view('article.show', [
            'article' => $article
        ]);
    }
}
