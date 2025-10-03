<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCollection;
use App\Repositories\ArticleCollectionRepositoryInterface;
use App\Services\ArticleCollectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ArticleCollectionController extends Controller
{
    public function __construct(private ArticleCollectionRepositoryInterface $collections)
    {
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 20);
        $perPage = max(1, min(100, $perPage));
        $items = $this->collections->paginate($perPage);
        return view('collections.index', [
            'collections' => $items,
        ]);
    }

    public function show(ArticleCollection $collection, Request $request)
    {
        $perPage = (int) $request->get('per_page', 24);
        $perPage = max(1, min(100, $perPage));
        // Paginate articles in the collection, eager-loading portal for the card component
        $articles = $collection->articles()
            ->with('portal')
            ->orderByDesc('published_at')
            ->paginate($perPage)
            ->withQueryString();

        return view('collections.show', [
            'collection' => $collection,
            'articles' => $articles,
        ]);
    }

    // Add two articles (a pair) into a deterministic collection for that pair
    public function collectPair(Request $request, ArticleCollectionService $articleCollectionService): RedirectResponse
    {
        $validated = $request->validate([
            'article_id_1' => ['required', 'integer', 'exists:articles,id'],
            'article_id_2' => ['required', 'integer', 'different:article_id_1', 'exists:articles,id'],
        ]);

        $article1 = Article::findOrFail((int) $validated['article_id_1']);
        $article2 = Article::findOrFail((int) $validated['article_id_2']);

        $articleCollectionService->setSameArticle($article1, $article2);

        return redirect()->back()->with('status', 'A cikkpár bekerült a gyűjteménybe.');
    }
}
