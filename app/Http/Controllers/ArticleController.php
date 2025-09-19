<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function show(Article $article, Request $request)
    {
        if($article->scraped_at === null || $request->has('refresh')) {
            /** @var ArticleService $articleService */
            $articleService = app(ArticleService::class);
            $articleService->setArticle($article);
            $articleService->scrapeArticle();
        }

        return view('article.show', [
            'article' => $article
        ]);
    }
}
