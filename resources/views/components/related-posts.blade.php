@props(['post'])

@php
    $relatedPosts = $post->getRelatedPosts(3);
@endphp

@if($relatedPosts->isNotEmpty())
<div class="mt-12 border-t pt-8">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Related Posts</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($relatedPosts as $relatedPost)
            <article class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                @if($relatedPost->featured_image)
                    <a href="{{ route('posts.public.show', $relatedPost->slug) }}">
                        <img src="{{ asset('storage/' . $relatedPost->featured_image) }}"
                        alt="{{ $relatedPost->title }}"
                        class="w-full h-48 object-cover rounded-t-lg">
                    </a>
                @endif

<div class="p-4">
    @if($relatedPost->category)
        <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">
            {{ $relatedPost->category->name }}
</span>
@endif

<h3 class="mt-2 text-lg font-semibold text-gray-900 dark:text-gray-100">
    <a href="{{ route('posts.public.show', $relatedPost->slug) }}"
    class="hover:text-indigo-600 dark:hover:text-indigo-400">
    {{ $relatedPost->title }}
</a>
</h3>

<p class="mt-2 text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
    {{ Str::limit(strip_tags($relatedPost->content), 100) }}
</p>

<div class="mt-3 text-xs text-gray-500 dark:text-gray-500">
    {{ $relatedPost->published_at->format('M d, Y') }}
</div>
</div>
</article>
        @endforeach
    </div>
</div>
@endif