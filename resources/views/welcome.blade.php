@extends('layouts.public')
@section('title', 'Home')

@section('content')
    <!-- Homepage (Welcome Page) of blog. -->
    <div class="mb-12 text-center">
        <h1 class="text-3xl sm:text-5xl font-bold text-gray-900 dark:text-gray-100 mb-4">Welcome to My Personal Blog</h1>
        <p class="text-xl text-gray-600">Sharing thoughts, ideas, and stories</p>
    </div>

    @if ($posts->count() > 0)
        <div class="grid gap-6">
            @foreach ($posts as $post)
                <article class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-700 hover:shadow-md transition">
                    <div class="flex flex-col sm:flex-row">
                        <!-- Content -->
                         <div class="flex-1 p-5 sm:p-6">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2">
                            <a href="{{ route('posts.public.show', $post->slug) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                {{ $post->title }}
                            </a>
                        </h2>

                        <p class="text-gray-600 dark:text-gray-300 mb-3 line-clamp-2">
                            {{$post->excerpt ?? Str::limit(strip_tags($post->content), 150)}}
                        </p>

                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                            <span>{{ $post->user?->name ?? 'Unknown' }}</span>
                            <span>•</span>
                            <span>{{ optional($post->published_at)->format('M d, Y') }}</span>
                            <span>•</span>
                            <span>{{ $post->reading_time }}</span>
                            @if ($post->category)
                                <span>•</span>
                                <a href="{{ route('categories.show', $post->category->slug) }}"
                                    class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                                    {{ $post->category->name }}
                                </a>
                            @endif
                        </div>
                    </div>

                        <!-- Thumbnail -->
                         @if ($post->featured_image)
                        <div class="sm:w-48 sm:h-auto flex-shrink-0">
                            <a href="{{ route('posts.public.show', $post->slug) }}">
                                <img src="{{ asset('storage/' . $post->featured_image) }}"
                                alt="{{ $post->title }}"
                                loading="lazy"
                                class="w-full h-40 sm:h-full object-cover sm:rounded-r-lg">
                            </a>
                        </div>
                        @endif
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
