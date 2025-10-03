@extends('layouts.app')

@section('page_title', 'Cikk-gyűjtemények')

@section('content')
    @if($collections->count() === 0)
        <p class="text-gray-600">Nincsenek gyűjtemények.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cím</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lead</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azonos tartalom?</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Létrehozva</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($collections as $row)
                    <tr>
                        <td class="px-4 py-2 font-semibold text-gray-900">
                            <a href="{{ route('collection.show', $row) }}" class="text-indigo-700 hover:underline">{{ $row->title }}</a>
                        </td>
                        <td class="px-4 py-2 text-gray-700">{{ Str::limit($row->lead, 180) }}</td>
                        <td class="px-4 py-2">
                            @if($row->is_same)
                                <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Igen</span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-600/20">Nem</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ optional($row->created_at)->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $collections->links() }}
        </div>
    @endif
@endsection
