@extends('layouts.app')

@section('page_title', 'Cikk-hasonlóságok')

@section('content')
    @if (session('status'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
    @endif

    @if($similarities->count() === 0)
        <p class="text-gray-600">Nincsenek hasonlósági adatok.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cikk 1</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cikk 2</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hasonlóság</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Számítva</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Művelet</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($similarities as $row)
                    <tr>
                        <td class="px-4 py-2">
                            <x-article-card :article="$row->article1"></x-article-card>
                        </td>
                        <td class="px-4 py-2">
                            <x-article-card :article="$row->article2"></x-article-card>
                        </td>
                        <td class="px-4 py-2 font-mono">{{ number_format($row->similarity, 6, '.', ' ') }}</td>
                        <td class="px-4 py-2">{{ optional($row->computed_at)->format('Y-m-d H:i') ?? '-' }}</td>
                        <td class="px-4 py-2">
                            <form method="POST" action="{{ route('collection.collect-pair', $row) }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                                    Hozzáadás gyűjteményhez
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $similarities->links() }}
        </div>
    @endif
@endsection
