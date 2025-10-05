@extends('layouts.app')

@section('page_title', 'Cikk-gyűjtemények')

@section('content')
    @if($collections->count() === 0)
        <div class="bg-muted text-muted-foreground border rounded-md p-6">
            Nincsenek gyűjtemények.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($collections as $articleCollection)
                <x-article-collection-card :articleCollection="$articleCollection" />
            @endforeach
        </div>

        <div class="mt-8">
            {{ $collections->links() }}
        </div>
    @endif
@endsection
