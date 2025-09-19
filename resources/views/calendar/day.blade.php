@extends('layouts.app')

@section('page_title')
    {{ $title ?? 'Cikkek a napon' }}
@endsection

@section('content')
    <div class="flex items-center justify-between mb-4">
        @php
            $monthUrl = route('calendar.index', ['year' => $day->year, 'month' => $day->month]);
        @endphp
        <a href="{{ $monthUrl }}" class="px-3 py-2 rounded border border-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700">&larr; Vissza a hónaphoz</a>
        <div class="text-xl font-semibold">{{ $day->format('Y. m. d.') }} napi cikkek</div>
        <div></div>
    </div>

    <x-articles :articles="$articles" />
@endsection
