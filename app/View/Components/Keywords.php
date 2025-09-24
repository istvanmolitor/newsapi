<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Keywords extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        private $keywords,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.keywords', [
            'keywords' => $this->keywords,
        ]);
    }
}
