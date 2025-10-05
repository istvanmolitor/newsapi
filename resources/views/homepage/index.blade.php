@extends('layouts.base')

@section('body')
    @include('layouts.includes.header')
    @include('layouts.includes.category-bar')

    <main>
        <div class="container mx-auto px-4 py-8">
            @if($featured)
                <section class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                    <article class="col-span-2 row-span-2 bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
                        @if(!empty($featured->main_image_src ?? $featured->list_image_src))
                            <a href="{{ route('article.show', $featured) }}" class="block">
                                <img src="{{ $featured->main_image_src ?: $featured->list_image_src }}" alt="" class="w-full h-96 object-cover" loading="lazy">
                            </a>
                        @endif
                        <div class="p-6">
                            <h2 class="text-2xl lg:text-3xl font-semibold leading-tight">
                                <a href="{{ route('article.show', $featured) }}" class="hover:underline">{{ $featured->title }}</a>
                            </h2>
                            @if(!empty($featured->lead))
                                <p class="mt-3 text-base text-slate-600 dark:text-slate-300">{{ $featured->lead }}</p>
                            @endif
                            <div class="mt-4 text-sm text-muted-foreground">
                                @if(!empty($featured->published_at))
                                    <span class="inline-flex items-center gap-1">
                                        <span class="font-medium">Megjelenés:</span>
                                        <time datetime="{{ \Carbon\Carbon::parse($featured->published_at)->toIso8601String() }}">
                                            {{ \Carbon\Carbon::parse($featured->published_at)->format('Y.m.d H:i') }}
                                        </time>
                                    </span>
                                @endif
                                <span class="inline-flex items-center gap-1 {{ empty($featured->published_at) ? '' : 'ml-3' }}">
                                    <span class="font-medium">Portál:</span>
                                    <a href="{{ route('portal.show', $featured->portal) }}">{{ $featured->portal }}</a>
                                </span>
                            </div>
                        </div>
                    </article>

                    @foreach($others->take(8) as $article)
                        <x-homepage-article-card :article="$article"></x-homepage-article-card>
                    @endforeach
                </section>
            @endif

            @if($others->count() > 7)
                <section class="mt-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold">Legújabb cikkek</h3>
                        <a href="{{ route('article.index') }}" class="text-sm text-blue-700 hover:underline">További cikkek</a>
                    </div>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($others->skip(7) as $article)
                            <x-homepage-article-card :article="$article"></x-homepage-article-card>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </main>

    @include('layouts.includes.footer')
@endsection
