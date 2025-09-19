<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use Illuminate\Http\Request;

class KeywordController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 50);
        $perPage = $perPage > 0 && $perPage <= 100 ? $perPage : 50;

        $keywords = Keyword::orderBy('keyword')
            ->paginate($perPage)
            ->withQueryString();

        return view('keyword.index', [
            'keywords' => $keywords,
            'title' => 'Kulcsszavak',
        ]);
    }

    public function show(Keyword $keyword, Request $request)
    {
        $perPage = (int) $request->input('per_page', 12);
        $perPage = $perPage > 0 && $perPage <= 50 ? $perPage : 12;

        $articles = $keyword->articles()
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        return view('keyword.show', [
            'keyword' => $keyword,
            'articles' => $articles,
            'title' => 'Kulcsszó: ' . $keyword->keyword,
        ]);
    }
}
