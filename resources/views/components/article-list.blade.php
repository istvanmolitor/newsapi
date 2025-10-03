<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($articles as $article)
        <x-article-card :article="$article"></x-article-card>
    @endforeach
</div>
