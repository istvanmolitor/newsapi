<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;

class Articles extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public LengthAwarePaginator $articles,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.articles', [
            'articles' => $this->articles
        ]);
    }
}
