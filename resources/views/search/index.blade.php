@extends('layouts.app')

@section('page_title')
    {{ $title ?? 'Keresés' }}
@endsection

@section('content')
    <x-article-list :articles="$articles"></x-article-list>
@endsection
