<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body>
@include('layouts.includes.header')
<main>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6">@yield('page_title')</h1>
        @yield('content')
    </div>
</main>
@include('layouts.includes.footer')
</body>
</html>
