@extends('layouts.app')

@section('page_title')
    Hírek
@endsection

@section('content')
    <div class="container mx-auto px-4 py-8">
        <x-articles :articles="$articles"></x-articles>
    </div>
@endsection
