@extends('layouts.article')

@section('page_title')
    Hír beküldése
@endsection

@section('content')
    @if(session('status'))
        <div class="mb-4 p-3 rounded-md bg-green-50 text-green-800 border border-green-200">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('article_submit.index') }}" method="post" class="space-y-4 max-w-2xl">
        @csrf
        <div>
            <label for="url" class="block text-sm font-medium mb-1">Hír URL-je</label>
            <input type="url" name="url" id="url" value="{{ old('url') }}" required placeholder="https://..." class="w-full border rounded-md px-3 py-2" />
            @error('url')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Beküldés</button>
            <a href="{{ url()->previous() }}" class="text-sm text-muted-foreground hover:underline">Mégse</a>
        </div>
        <p class="text-sm text-muted-foreground">Add meg a hír URL-jét. A rendszer létrehozza a bejegyzést és automatikusan megpróbálja letölteni a tartalmát.</p>
    </form>
@endsection

@section('sidebar')
@endsection
