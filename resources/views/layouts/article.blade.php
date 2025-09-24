@extends('layouts.base')

@section('body')
    @include('layouts.includes.header')
    <main>
        <div class="container mx-auto px-4 py-8">
            <article class="max-w-3xl mx-auto bg-card text-card-foreground rounded-lg shadow-sm border border-border p-6">
                <h1 class="text-3xl font-semibold mb-6">@yield('page_title')</h1>
                @yield('content')
            </article>
        </div>
    </main>
    @include('layouts.includes.footer')
@endsection
