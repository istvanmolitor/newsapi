@extends('layouts.app')

@section('page_title', 'Cikk-gyűjtemények')

@section('content')
    @if($collections->count() === 0)
        <div class="bg-muted text-muted-foreground border rounded-md p-6">
            Nincsenek gyűjtemények.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($collections as $row)
                <article class="bg-card text-card-foreground rounded-lg shadow-sm border border-border p-4 flex flex-col">
                    <header class="flex items-start justify-between gap-3">
                        <h2 class="text-lg font-semibold leading-tight">
                            <a href="{{ route('collection.show', $row) }}" class="hover:underline">{{ $row->title }}</a>
                        </h2>
                        <span class="shrink-0 inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">
                            {{ $row->articles_count ?? $row->articles()->count() }} db
                        </span>
                    </header>

                    @if(!empty($row->lead))
                        <p class="mt-2 text-sm text-muted-foreground line-clamp-3">{{ $row->lead }}</p>
                    @endif

                    <div class="mt-3 text-xs text-muted-foreground flex items-center gap-3">
                        <span>Azonos tartalom?
                            @if($row->is_same)
                                <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Igen</span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-0.5 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-600/20">Nem</span>
                            @endif
                        </span>
                        <span>Létrehozva: {{ optional($row->created_at)->format('Y-m-d H:i') }}</span>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('collection.show', $row) }}" class="inline-flex items-center gap-2 text-sm bg-secondary text-secondary-foreground px-3 py-1.5 rounded-md border hover:bg-accent">
                            Megnyitás
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $collections->links() }}
        </div>
    @endif
@endsection
