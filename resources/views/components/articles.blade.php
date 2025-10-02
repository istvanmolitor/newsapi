@if($articles->count() === 0)
    <div class="bg-muted text-muted-foreground border rounded-md p-6">
        Nincs megjeleníthető hír.
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($articles as $article)
            <article class="bg-card text-card-foreground rounded-lg shadow-sm border border-border overflow-hidden flex flex-col">
                @if(!empty($article->list_image_src))
                    <a href="{{ route('article.show', $article) }}" class="block">
                        <img src="{{ $article->list_image_src }}" class="w-full h-44 object-cover" loading="lazy" alt="">
                    </a>
                @else
                    <a href="{{ route('article.show', $article) }}" class="block">
                        <div class="w-full h-44 bg-muted flex items-center justify-center text-muted-foreground select-none" aria-hidden="true">
                            <!-- spacer for missing image -->
                        </div>
                    </a>
                @endif
                <div class="p-4 flex-1 flex flex-col">
                    <h2 class="text-lg font-semibold leading-tight">
                        <a href="{{ route('article.show', $article) }}" class="hover:underline">
                            {{ $article->title }}
                        </a>
                    </h2>
                    @if(!empty($article->lead))
                        <p class="mt-2 text-sm text-muted-foreground line-clamp-3">{{ $article->lead }}</p>
                    @endif
                    <div class="mt-3 text-xs text-muted-foreground">
                        @if(!empty($article->published_at))
                            <span class="inline-flex items-center gap-1">
                                <span class="font-medium">Megjelenés:</span>
                                <time datetime="{{ \Carbon\Carbon::parse($article->published_at)->toIso8601String() }}">
                                    {{ \Carbon\Carbon::parse($article->published_at)->format('Y.m.d H:i') }}
                                </time>
                            </span>
                        @endif
                        <span class="inline-flex items-center gap-1 {{ empty($article->published_at) ? '' : 'ml-3' }}">
                            <span class="font-medium">Rögzítve:</span>
                            <time datetime="{{ \Carbon\Carbon::parse($article->published_at)->toIso8601String() }}">
                                {{ \Carbon\Carbon::parse($article->published_at)->format('Y.m.d H:i') }}
                            </time>
                        </span>
                    </div>
                    <div class="mt-3 text-xs text-muted-foreground">
                        <span class="inline-flex items-center gap-1">Portal:</span>
                        <a href="{{ route('portal.show', $article->portal) }}">{{ $article->portal }}</a>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('article.show', $article) }}" class="inline-flex items-center gap-2 text-sm bg-secondary text-secondary-foreground px-3 py-1.5 rounded-md border hover:bg-accent">
                            Megnyitás
                        </a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $articles->links() }}
    </div>
@endif
