<?php

namespace App\Http\Controllers;

use App\Models\Portal;
use App\Models\Article;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 24);
        $perPage = $perPage > 0 && $perPage <= 100 ? $perPage : 24;

        $portals = Portal::orderBy('name')->paginate($perPage)->withQueryString();

        return view('portal.index', [
            'portals' => $portals,
            'title' => 'Portálok',
        ]);
    }

    public function show(Portal $portal, Request $request)
    {
        $perPage = (int) $request->input('per_page', 12);
        $perPage = $perPage > 0 && $perPage <= 50 ? $perPage : 12;

        $articles = Article::where('portal_id', $portal->id)
            ->orderByDesc('published_at')
            ->orderByDesc('title')
            ->paginate($perPage)
            ->withQueryString();

        return view('portal.show', [
            'portal' => $portal,
            'articles' => $articles,
            'title' => (string) $portal,
        ]);
    }
}
