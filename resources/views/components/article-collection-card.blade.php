<article class="bg-card text-card-foreground rounded-lg shadow-sm border border-border p-4 flex flex-col">
    <header class="flex items-start justify-between gap-3">
        <h2 class="text-lg font-semibold leading-tight">
            <a href="{{ route('collection.show', $articleCollection) }}" class="hover:underline">{{ $articleCollection->title }}</a>
        </h2>
        <span class="shrink-0 inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">
                            {{ $articleCollection->articles_count ?? $articleCollection->articles()->count() }} db
                        </span>
    </header>

    @if(!empty($articleCollection->lead))
        <p class="mt-2 text-sm text-muted-foreground line-clamp-3">{{ $articleCollection->lead }}</p>
    @endif

    <div class="mt-3 text-xs text-muted-foreground flex items-center gap-3">
        <span>Létrehozva: {{ optional($articleCollection->created_at)->format('Y-m-d H:i') }}</span>
    </div>

    <div class="mt-4">
        <a href="{{ route('collection.show', $articleCollection) }}" class="inline-flex items-center gap-2 text-sm bg-secondary text-secondary-foreground px-3 py-1.5 rounded-md border hover:bg-accent">
            Megnyitás
        </a>
    </div>
</article>
