@extends('layouts.app')

@section('page_title')
    {{ $title ?? 'Kulcsszavak' }}
@endsection

@section('content')
    @if($keywords->count() === 0)
        <p class="text-muted">Nincs megjeleníthető kulcsszó.</p>
    @else
        <x-keywords :keywords="$keywords"></x-keywords>
        <nav class="mt-6">
            {{ $keywords->links() }}
        </nav>
    @endif
@endsection
