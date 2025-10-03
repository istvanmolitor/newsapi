@extends('layouts.base')

@section('body')
    @include('layouts.includes.header')
    <main>
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <article class="md:col-span-2 bg-card text-card-foreground rounded-lg shadow-sm border border-border p-6">
                    <h1 class="text-3xl font-semibold mb-6">@yield('page_title')</h1>
                    @yield('content')
                </article>
                <aside class="md:col-span-1">
                    @yield('sidebar')
                </aside>
            </div>
        </div>
    </main>
    @include('layouts.includes.footer')
@endsection
