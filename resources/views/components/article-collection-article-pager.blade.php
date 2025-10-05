@if(!empty($sameCollection))
    <div class="mt-8 border-t pt-4 bg-gray-50 p-6">
        <div class="flex items-center justify-between gap-4">
            <div class="flex-1">
                @if(!empty($collectionPagerPrev))
                    <a href="{{ route('article.show', $collectionPagerPrev) }}" class="inline-flex items-center justify-center gap-2 text-sm bg-blue-950 text-white px-4 py-2 rounded-md hover:bg-blue-700 w-32">&larr; Előző</a>
                @endif
            </div>
            <div class="flex-1 text-center text-sm text-muted-foreground">
                <span>Gyűjtemény:</span>
                <a href="{{ route('collection.show', $sameCollection) }}" class="font-medium hover:underline">{{ $sameCollection->title }}</a>
            </div>
            <div class="flex-1 text-right">
                @if(!empty($collectionPagerNext))
                    <a href="{{ route('article.show', $collectionPagerNext) }}" class="inline-flex items-center justify-center gap-2 text-sm bg-blue-950 text-white px-4 py-2 rounded-md hover:bg-blue-700 w-32">Következő &rarr;</a>
                @endif
            </div>
        </div>
    </div>
@endif
