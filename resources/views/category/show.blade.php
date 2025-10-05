@extends('layouts.app')

@section('page_title')
    {{ $category->name }}
@endsection

@section('content')
    @if($articles->isEmpty())
        <p class="text-muted-foreground">Nincs megjeleníthető cikk ebben a kategóriában.</p>
    @else
        <x-articles :articles="$articles"></x-articles>
    @endif
@endsection
