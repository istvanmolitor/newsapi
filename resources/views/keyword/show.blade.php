@extends('layouts.app')

@section('page_title')
    Kulcsszó: {{ $keyword->keyword }}
@endsection

@section('content')
    <nav class="mb-3"><a href="{{ route('keyword.index') }}">← Vissza a kulcsszavakhoz</a></nav>
    <x-articles :articles="$articles"></x-articles>
@endsection
