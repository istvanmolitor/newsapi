@extends('layouts.app')

@section('page_title')
    {{ $title ?? 'Naptár' }}
@endsection

@section('content')
    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('calendar.index', ['year' => $prevYear, 'month' => $prevMonth]) }}" class="px-3 py-2 rounded border border-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700">&larr; Előző hónap</a>
        <div class="text-xl font-semibold">{{ $year }}. {{ ucfirst($monthName) }}</div>
        <a href="{{ route('calendar.index', ['year' => $nextYear, 'month' => $nextMonth]) }}" class="px-3 py-2 rounded border border-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700">Következő hónap &rarr;</a>
    </div>

    <div class="grid grid-cols-7 gap-2 mb-2 text-center text-sm text-slate-500">
        <div>H</div>
        <div>K</div>
        <div>SZE</div>
        <div>CS</div>
        <div>P</div>
        <div>SZO</div>
        <div>V</div>
    </div>

    <div class="grid grid-cols-7 gap-2">
        @foreach($days as $d)
            @php
                /** @var \Carbon\Carbon $date */
                $date = $d['date'];
            @endphp
            <a href="{{ request()->fullUrlWithQuery(['year' => $date->year, 'month' => $date->month, 'day' => $date->day]) }}"
               class="block rounded border p-3 text-center transition
                    {{ $d['isCurrentMonth'] ? 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700' : 'bg-slate-50 dark:bg-slate-900/40 border-slate-100 dark:border-slate-800 text-slate-400' }}
                    {{ $d['isToday'] ? 'ring-2 ring-indigo-500' : '' }}">
                <div class="text-lg font-medium">{{ $date->day }}</div>
            </a>
        @endforeach
    </div>

    @if(request()->has('day'))
        <div class="mt-6 p-4 rounded border border-slate-200 dark:border-slate-700">
            <div class="font-semibold mb-2">Kiválasztott nap:</div>
            <div>{{ sprintf('%04d-%02d-%02d', (int)request('year', $year), (int)request('month', $month), (int)request('day')) }}</div>
        </div>
    @endif
@endsection
