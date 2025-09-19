@extends('layouts.app')

@section('page_title')
    {{ $title ?? 'Kulcsszavak' }}
@endsection

@section('content')
    @if($keywords->count() === 0)
        <p class="text-muted">Nincs megjeleníthető kulcsszó.</p>
    @else
        <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($keywords as $k)
                <li>
                    <a href="{{ route('keyword.show', $k) }}" class="inline-block w-full rounded-lg border border-slate-200 bg-white px-4 py-3 text-slate-800 shadow-sm hover:shadow transition hover:border-slate-300 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                        <span class="truncate"># {{ $k->keyword }}</span>
                    </a>
                </li>
            @endforeach
        </ul>

        <nav class="mt-6">
            {{ $keywords->links() }}
        </nav>
    @endif
@endsection
