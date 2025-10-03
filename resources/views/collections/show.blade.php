@extends('layouts.app')

@section('page_title', $collection->title)

@section('content')
    <div class="mb-6">
        @if(!empty($collection->lead))
            <p class="mt-2 text-gray-700">{{ $collection->lead }}</p>
        @endif
        <div class="mt-2 text-sm text-gray-600 flex items-center gap-3">
            <span>Létrehozva: {{ optional($collection->created_at)->format('Y-m-d H:i') }}</span>
            <span>Elemek: {{ $articles->total() }}</span>
        </div>
    </div>

    <x-articles :articles="$articles" />
@endsection
