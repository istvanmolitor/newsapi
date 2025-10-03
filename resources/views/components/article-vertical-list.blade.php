<div>
    @foreach($articles as $article)
        <div class="mb-6">
            <x-article-card :article="$article"></x-article-card>
        </div>
    @endforeach
</div>
