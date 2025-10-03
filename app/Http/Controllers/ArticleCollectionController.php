<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCollection;
use App\Models\ArticleSimilarity;
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
    public function collectPair(ArticleSimilarity $articleSimilarity, ArticleCollectionService $articleCollectionService): RedirectResponse
    {
        $articleCollectionService->joinSimilarArticles($articleSimilarity);
        $articleSimilarity->delete();
        return redirect()->back()->with('status', 'A cikkpár bekerült a gyűjteménybe.');
    }
}
