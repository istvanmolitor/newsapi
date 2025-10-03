<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use App\Services\ElasticArticleService;
use Illuminate\Http\Request;

class ArticleSubmitController extends Controller
{
    public function index()
    {
        return view('article_submit.index');
    }

    public function submit(Request $request, ArticleService $articleService, ElasticArticleService $elasticService)
    {
        $validated = $request->validate([
            'url' => ['required', 'url']
        ]);

        $url = $validated['url'];

        $article = $articleService->scrapeByUrl($url);
        if(!$article) {
            return back()->withErrors(['url' => 'Hibás url.'])->withInput();
        }

        $elasticService->indexArticle($article);

        return redirect()->route('article.show', $article)->with('status', 'A hír beküldése megtörtént, feldolgozás alatt.');
    }
}
