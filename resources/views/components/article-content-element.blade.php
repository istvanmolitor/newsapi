@if($articleContentElement->type === \App\Enums\ArticleContentElementType::Paragraph)
    <p class="text-justify my-3">{{ $articleContentElement->content }}</p>
@elseif($articleContentElement->type === \App\Enums\ArticleContentElementType::Quote)
    <blockquote>{{ $articleContentElement->content }}</blockquote>
@elseif($articleContentElement->type === \App\Enums\ArticleContentElementType::Heading)
    <h2 class="font-extrabold">{{ $articleContentElement->content }}</h2>
@elseif($articleContentElement->type === \App\Enums\ArticleContentElementType::List)
    @php $content = $articleContentElement->getContent(); @endphp
    <ul class="list-disc list-inside my-3">
        @foreach($content as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>
@elseif($articleContentElement->type === \App\Enums\ArticleContentElementType::Image)
    @php $content = $articleContentElement->getContent(); @endphp
    <figure class="mb-6">
        <img src="{{ $content->src }}" alt="{{ $content->alt ?? $article->title }}" class="w-full h-auto rounded-md border object-cover" loading="lazy">
        @if($content->author)
            <div>Szerző: {{ $content->author }}</div>
        @endif
    </figure>
@elseif($articleContentElement->type === \App\Enums\ArticleContentElementType::Video)
    @php $content = $articleContentElement->getContent(); @endphp
    <x-video-component :src="$content->src" :type="$content->type"></x-video-component>
@else
    <div>
        @dump($articleContentElement)
    </div>
@endif
