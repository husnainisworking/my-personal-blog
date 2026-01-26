@extends('layouts.public')
@section('title', 'Home')

@section('content')
    <!-- Homepage (Welcome Page) of blog. -->
    <div class="mb-12 text-center">
        <h1 class="text-3xl sm:text-5xl font-bold text-gray-900 dark:text-gray-100 mb-4">Welcome to My Personal Blog</h1>
        <p class="text-xl text-gray-600">Sharing thoughts, ideas, and stories</p>
    </div>

    @if ($posts->count() > 0)
        <div class="grid gap-8">
            @foreach ($posts as $post)
                <article class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition">
                    <div class="p-5 sm:p-8">
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">
                            <a href="{{ route('posts.public.show', $post->slug) }}" class="hover:text-indigo-600 dark:text-indigo-400">
                                {{ $post->title }}
                            </a>
                        </h2>

                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs sm:text-sm text-gray-600 mb-4">
                            <span>{{ $post->user?->name ?? 'Unknown Author' }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ optional($post->published_at)->format('F d, Y') }}</span>
                            <span class="mx-2">•</span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $post->reading_time }}
                            </span>

                            <span class="mx-2">•</span>
                            <span class="flex items-center gap-1" title="Total views">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ $post->formatted_views }} views
                            </span>
                            @if ($post->category)
                                <span class="mx-2">•</span>
                                <a href="{{ route('categories.show', $post->category->slug) }}"
                                    class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                                    {{ $post->category->name }}
                                </a>
                            @endif
                        </div>

                        @if ($post->excerpt)
                            <p class="text-gray-700 text-lg mb-4 leading-relaxed">{{ $post->excerpt }}</p>
                        @else
                            <p class="text-gray-700 text-lg mb-4 leading-relaxed">
                                {{ Str::limit(strip_tags($post->content), 300) }}</p>
                        @endif

                        @if ($post->tags->count() > 0)
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach ($post->tags as $tag)
                                    <a href="{{ route('tags.show', $tag->slug) }}"
                                        class="bg-gray-100 text-gray-700 border border-gray-200 px-3 py-1 rounded-full text-sm hover:bg-gray-200">
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                        <a href="{{ route('posts.public.show', $post->slug) }}"
                            class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-medium text-lg">
                            Read more →
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    @else
        <div class="text-center py-20">
            <p class="text-gray-500 text-xl">No posts yet. Check back soon!</p>
        </div>
    @endif
@endsection
