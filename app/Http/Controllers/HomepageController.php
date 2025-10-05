<?php

namespace App\Http\Controllers;

use App\Models\Article;

class HomepageController extends Controller
{
    public function index()
    {
        // Fetch latest articles, show one featured and a handful of others
        $articles = Article::orderByDesc('published_at')
            ->orderByDesc('title')
            ->limit(9)
            ->get();

        $featured = $articles->first();
        $others = $articles->skip(1);

        return view('homepage.index', [
            'featured' => $featured,
            'others' => $others,
            'title' => 'Főoldal',
        ]);
    }
}
