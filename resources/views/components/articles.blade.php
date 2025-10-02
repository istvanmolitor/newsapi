@if($articles->count() === 0)
    <div class="bg-muted text-muted-foreground border rounded-md p-6">
        Nincs megjeleníthető hír.
    </div>
@else
    <x-article-list :articles="$articles"></x-article-list>

    <div class="mt-8">
        {{ $articles->links() }}
    </div>
@endif
