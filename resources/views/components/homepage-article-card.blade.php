<article class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
    @if(!empty($article->list_image_src))
        <a href="{{ route('article.show', $article) }}" class="block">
            <img src="{{ $article->list_image_src }}" class="w-full h-32 object-cover" loading="lazy" alt="">
        </a>
    @endif
    <div class="p-3">
        <h2 class="text-base font-semibold leading-tight">
            <a href="{{ route('article.show', $article) }}" class="hover:underline">
                {{ $article->title }}
            </a>
        </h2>
    </div>
</article>
