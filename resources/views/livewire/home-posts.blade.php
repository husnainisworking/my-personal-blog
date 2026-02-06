<div>
    @if ($posts->count() >0)
    <div class="grid gap-6">
        @foreach($posts as $post)
        <article
                class="bg-white dark:bg-slate-800 rounded-lg shadow-sm hover:shadow-md transition"
                wire:key="post-{{$post->id }}"
                >
                    <div class="flex flex-col sm:flex-row">
                        <!-- Content -->
                         <div class="flex-1 p-5 sm:p-6">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2">
                                <a href="{{ route('posts.public.show', $post->slug) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                    {{ $post->title }}
                                </a>
                            </h2>

                            <p class="text-gray-600 dark:text-gray-300 mb-3 line-clamp-2">
                                {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 150) }}
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
                     <div class="w-full sm:w-48 aspect-[4/3] flex-shrink-0 overflow-hidden sm:rounded-r-lg">
                        <a href="{{ route('posts.public.show', $post->slug) }}" class="block w-full h-full">
                            <img src="{{ asset('storage/' . $post->featured_image) }}"
                            alt="{{ $post->title }}"
                            @if($loop->first)
                                fetchpriority="high"
                            @else
                                loading="lazy"
                            @endif
                            class="w-full h-full object-cover">
                        </a>
                    </div>
                @endif
            </div>
        </article>
        @endforeach
    </div>

    <!-- Pagination -->
     @if($posts->hasPages())
        <div class="mt-8">
            {{ $posts->links() }}
        </div>
     @endif
@else
    <div class="text-center py-20">
        <!-- Empty posts icon -->
         <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
        </svg>    
        <p class="text-gray-500 text-xl">No posts yet. Check back soon!</p>
    </div>
@endif
</div>