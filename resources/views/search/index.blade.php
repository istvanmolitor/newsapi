@extends('layouts.app')

@section('page_title')
    {{ $title ?? 'Keresés' }}
@endsection

@section('content')
    <x-articles :articles="$articles"></x-articles>
@endsection
