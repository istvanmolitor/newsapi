@extends('layouts.app')

@section('page_title')
    {{ $title ?? 'Cikkek a napon' }}
@endsection

@section('content')
    <div class="flex items-center justify-between mb-4">
        @php
            $monthUrl = route('calendar.index', ['year' => $day->year, 'month' => $day->month]);
        @endphp

        <nav class="mb-3"><a href="{{ $monthUrl }}">&larr; Vissza a hónaphoz</a></nav>
    </div>

    <x-articles :articles="$articles" />
@endsection
