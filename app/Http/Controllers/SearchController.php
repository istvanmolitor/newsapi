<?php

namespace App\Http\Controllers;

use App\Repositories\KeywordRepositoryInterface;
use App\Services\ElasticService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request, ElasticService $elasticService, KeywordRepositoryInterface $keywordRepository)
    {
        $q = trim((string) $request->input('q', ''));

        $keywords = $keywordRepository->search($q, 6);
        $articles = $elasticService->searchArticles($q, 9);

        return view('search.index', [
            'keywords' => $keywords,
            'articles' => $articles,
            'q' => $q,
            'title' => $q !== '' ? "Keresés: {$q}" : 'Keresés',
        ]);
    }
}
