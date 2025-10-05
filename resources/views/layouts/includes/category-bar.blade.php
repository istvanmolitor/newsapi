<?php
use App\Models\Category;
$__categories = cache()->remember('header_categories', 60, function () {
    return Category::query()->orderBy('order')->orderBy('name')->get(['id','name']);
});
?>
@if($__categories->isNotEmpty())
    <div class="bg-blue-800/90 border-t border-blue-700">
        <div class="container mx-auto px-4">
            <nav class="flex gap-4 overflow-x-auto py-2" aria-label="Kategóriák">
                @foreach($__categories as $cat)
                    <a href="{{ route('category.show', $cat) }}" class="text-sm text-white/90 hover:text-white whitespace-nowrap py-1">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </nav>
        </div>
    </div>
@endif
