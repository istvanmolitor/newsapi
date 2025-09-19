@extends('layouts.app')

@section('Page title')
    {{ $title ?? 'Kulcsszavak' }}
@endsection

@section('content')
    @if($keywords->count() === 0)
        <p>Nincs megjeleníthető kulcsszó.</p>
    @else
        <ul>
            @foreach($keywords as $k)
                <li>
                    <a href="{{ route('keyword.show', $k) }}">{{ $k->keyword }}</a>
                </li>
            @endforeach
        </ul>

        <nav>
            {{ $keywords->links() }}
        </nav>
    @endif
@endsection
