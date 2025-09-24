@extends('layouts.app')

@section('page_title')
    {{ $portal->name }}
@endsection

@section('content')
    <nav class="mb-3"><a href="{{ route('portal.index') }}">← Vissza a portálokhoz</a></nav>
    <div class="text-sm text-muted-foreground mb-3">
        <a href="{{ $portal->domain }}" class="underline" target="_blank" rel="noopener noreferrer">{{ $portal->domain }}</a>
    </div>
    <x-articles :articles="$articles"></x-articles>
@endsection
