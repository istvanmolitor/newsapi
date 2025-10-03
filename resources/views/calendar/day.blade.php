@extends('layouts.app')

@section('page_title')
    {{ $title ?? 'Cikkek a napon' }}
@endsection

@section('content')
    <div class="flex items-center justify-between mb-4">
        @php
            $monthUrl = route('calendar.index', ['year' => $day->year, 'month' => $day->month]);
        @endphp

        <nav class="mb-3 flex items-center gap-3">
            <a href="{{ route('calendar.day', ['date' => $prevDay->format('Y-m-d'), 'per_page' => request('per_page')]) }}" class="px-3 py-2 rounded border border-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700">&larr; Előző nap</a>
            <a href="{{ $monthUrl }}" class="px-3 py-2 rounded border border-indigo-200 text-indigo-700 bg-indigo-50 hover:bg-indigo-100 dark:border-indigo-700/50 dark:text-indigo-300 dark:bg-indigo-900/30">Vissza a hónaphoz</a>
            <a href="{{ route('calendar.day', ['date' => $nextDay->format('Y-m-d'), 'per_page' => request('per_page')]) }}" class="px-3 py-2 rounded border border-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700">Következő nap &rarr;</a>
        </nav>
    </div>

    <x-articles :articles="$articles" />
@endsection
