@extends('layouts.base')

@section('body')
    @include('layouts.includes.header')
    @include('layouts.includes.category-bar')
    <main>
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-semibold mb-6">@yield('page_title')</h1>
            @yield('content')
        </div>
    </main>
    @include('layouts.includes.footer')
@endsection
