@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <article class="max-w-3xl mx-auto bg-card text-card-foreground rounded-lg shadow-sm border border-border p-6">
            <header class="mb-6">
                <h1 class="text-3xl font-semibold leading-tight tracking-tight">{{ $article->title ?? 'Cím nélkül' }}</h1>
                @if(!empty($article->lead))
                    <p class="mt-3 text-muted-foreground">{{ $article->lead }}</p>
                @endif

                <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-muted-foreground">
                    @if(!empty($article->author))
                        <span class="inline-flex items-center gap-1">
                            <span class="font-medium">Szerző:</span>
                            <span>{{ $article->author }}</span>
                        </span>
                    @endif
                    @if(!empty($article->published_at))
                        <span class="inline-flex items-center gap-1">
                            <span class="font-medium">Megjelenés:</span>
                            <time datetime="{{ \Carbon\Carbon::parse($article->published_at)->toIso8601String() }}">
                                {{ \Carbon\Carbon::parse($article->published_at)->format('Y.m.d H:i') }}
                            </time>
                        </span>
                    @endif
                </div>
            </header>

            @if(!empty($article->main_image_src))
                <figure class="mb-6">
                    <img src="{{ $article->main_image_src }}" alt="{{ $article->main_image_alt ?? $article->title }}" class="w-full h-auto rounded-md border object-cover" loading="lazy">
                </figure>
            @endif

            <div class="prose max-w-none prose-neutral dark:prose-invert">
                @foreach($article->articleContentElements as $articleContentElement)
                    <x-article-content-element :element="$articleContentElement" />
                @endforeach
            </div>

            <footer class="mt-8 border-t pt-4 flex items-center justify-between">
                <a href="{{ url()->previous() }}" class="text-sm text-muted-foreground hover:text-foreground">&larr; Vissza</a>
                @if(!empty($article->url))
                    <a class="inline-flex items-center gap-2 text-sm bg-secondary text-secondary-foreground px-3 py-1.5 rounded-md border hover:bg-accent" href="{{ $article->url }}" target="_blank" rel="noopener">Eredeti cikk megnyitása</a>
                @endif
            </footer>
        </article>
    </div>
@endsection
