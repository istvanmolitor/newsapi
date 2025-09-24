@extends('layouts.app')

@section('page_title')
    Hírek
@endsection

@section('content')
    <x-articles :articles="$articles"></x-articles>
@endsection
