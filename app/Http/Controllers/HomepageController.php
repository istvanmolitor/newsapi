<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Repositories\ArticleCollectionRepositoryInterface;

class HomepageController extends Controller
{
    public function index(ArticleCollectionRepositoryInterface $collectionRepository)
    {
        // Fetch latest articles, show one featured and a handful of others
        $articles = Article::orderByDesc('published_at')
            ->orderByDesc('title')
            ->limit(9)
            ->get();

        $featured = $articles->first();
        $others = $articles->skip(1);

        // Collect 4 latest articles that are part of any collection, excluding already shown ones
        $shownIds = $articles->pluck('id');

        $articleCollections = $collectionRepository->getForHomepage(4);

        return view('homepage.index', [
            'featured' => $featured,
            'others' => $others,
            'articleCollections' => $articleCollections,
            'title' => 'Főoldal',
        ]);
    }
}
