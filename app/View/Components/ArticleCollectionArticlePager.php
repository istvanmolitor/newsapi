<?php

namespace App\View\Components;

use App\Models\Article;
use App\Repositories\ArticleCollectionRepositoryInterface;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ArticleCollectionArticlePager extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        private Article $article,
        private ArticleCollectionRepositoryInterface $articleCollectionRepository,
    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $collectionPagerPrev = null;
        $collectionPagerNext = null;

        $sameCollection = $this->articleCollectionRepository->getSameCollectionByArticle($this->article);

        if ($sameCollection) {
            $articlesInCollection = $sameCollection->articles()
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->get();

            if($articlesInCollection->count() > 1) {
                $index = $articlesInCollection->search(function ($a) {
                    return (int)$a->id === (int)$this->article->id;
                });

                if ($index !== false) {
                    if ($articlesInCollection->has($index - 1)) {
                        $collectionPagerPrev = $articlesInCollection->get($index - 1);
                    }
                    else {
                        $collectionPagerPrev = $articlesInCollection->last();
                    }
                    if ($articlesInCollection->has($index + 1)) {
                        $collectionPagerNext = $articlesInCollection->get($index + 1);
                    }
                    else {
                        $collectionPagerNext = $articlesInCollection->first();
                    }
                }
            }
        }

        return view('components.article-collection-article-pager',[
            'article' => $this->article,
            'sameCollection' => $sameCollection,
            'collectionPagerPrev' => $collectionPagerPrev,
            'collectionPagerNext' => $collectionPagerNext,
        ]);
    }
}
