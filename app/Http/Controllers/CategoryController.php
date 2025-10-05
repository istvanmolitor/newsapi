<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display articles that belong to the given category based on keyword mapping.
     */
    public function show(Category $category, Request $request)
    {
        $perPage = (int) $request->input('per_page', 12);
        $perPage = $perPage > 0 && $perPage <= 50 ? $perPage : 12;

        $category->load('keywords:id');
        $keywordIds = $category->keywords->pluck('id');

        $articles = Article::query()
            ->when($keywordIds->isNotEmpty(), function ($q) use ($keywordIds) {
                $q->whereHas('keywords', function ($q2) use ($keywordIds) {
                    $q2->whereIn('keywords.id', $keywordIds);
                });
            }, function ($q) {
                // If category has no keywords, no articles should match
                $q->whereRaw('0 = 1');
            })
            ->orderByDesc('published_at')
            ->orderByDesc('title')
            ->paginate($perPage)
            ->withQueryString();

        return view('category.show', [
            'category' => $category,
            'articles' => $articles,
            'title' => $category->name,
        ]);
    }
}
