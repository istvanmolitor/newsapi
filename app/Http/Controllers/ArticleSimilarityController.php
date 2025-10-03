<?php

namespace App\Http\Controllers;

use App\Models\ArticleSimilarity;
use Illuminate\Contracts\View\View;

class ArticleSimilarityController extends Controller
{
    /**
     * Display a listing of the article similarities ordered by highest similarity first.
     */
    public function index(): View
    {
        $similarities = ArticleSimilarity::with(['article1', 'article2'])
            ->orderByDesc('similarity')
            ->paginate(50)
            ->withQueryString();

        return view('similarity.index', [
            'similarities' => $similarities,
        ]);
    }
}
