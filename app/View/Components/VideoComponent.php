<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class VideoComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        private string $src,
        private null|string $type = null,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.video-component', [
            'src' => $this->src,
            'type' => $this->type,
        ]);
    }
}
