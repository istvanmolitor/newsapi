@extends('layouts.app')

@section('page_title', $collection->title)

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $collection->title }}</h1>
        @if(!empty($collection->lead))
            <p class="mt-2 text-gray-700">{{ $collection->lead }}</p>
        @endif
        <div class="mt-2 text-sm text-gray-600 flex items-center gap-3">
            <span>Azonos tartalom?
                @if($collection->is_same)
                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Igen</span>
                @else
                    <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-0.5 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-600/20">Nem</span>
                @endif
            </span>
            <span>Létrehozva: {{ optional($collection->created_at)->format('Y-m-d H:i') }}</span>
            <span>Elemek: {{ $articles->total() }}</span>
        </div>
    </div>

    @if($articles->count() === 0)
        <p class="text-gray-600">A gyűjtemény üres.</p>
    @else
        <x-article-list :articles="$articles" />
        <div class="mt-6">
            {{ $articles->links() }}
        </div>
    @endif
@endsection
