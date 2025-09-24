@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">{{ $title ?? 'Portálok' }}</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($portals as $portal)
                <a href="{{ route('portal.show', $portal) }}" class="block border rounded p-4 hover:bg-accent/30 transition-colors">
                    <div class="text-lg font-semibold">{{ $portal->name }}</div>
                    <div class="text-sm text-gray-600">{{ $portal->domain }}</div>
                    <div class="mt-2 text-xs text-gray-500">Cikkek: {{ $portal->articles()->count() }}</div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $portals->links() }}
        </div>
    </div>
@endsection

