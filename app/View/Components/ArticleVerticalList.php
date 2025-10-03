<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ArticleVerticalList extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        protected $articles,
        protected bool $noImage = true
    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.article-vertical-list', [
            'articles' => $this->articles,
            'noImage' => $this->noImage,
        ]);
    }
}
