@extends('layouts.article')

@section('page_title')
    {{ $article->title ?? 'Cím nélkül' }}
@endsection

@section('content')
    <header class="mb-6">

        <span class="inline-flex items-center gap-1">
            <span class="font-medium"><a href="{{ route('portal.show', $article->portal) }}">{{ $article->portal }}</a></span>
        </span>

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
            <span class="inline-flex items-center gap-1">
                <span class="font-medium">Rögzítve:</span>
                <time datetime="{{ \Carbon\Carbon::parse($article->created_at)->toIso8601String() }}">
                    {{ \Carbon\Carbon::parse($article->created_at)->format('Y.m.d H:i') }}
                </time>
            </span>
        </div>

        @if(!empty($article->main_image_src))
            <figure class="mb-6">
                <img src="{{ $article->main_image_src }}" alt="{{ $article->main_image_alt ?? $article->title }}" class="w-full h-auto rounded-md border object-cover" loading="lazy">
            </figure>
        @endif

        <x-keywords :keywords="$article->keywords"></x-keywords>
    </header>

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
@endsection

@section('sidebar')
    <x-article-vertical-list :articles="$recommendedArticles"></x-article-vertical-list>
@endsection
