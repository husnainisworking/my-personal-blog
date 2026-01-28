@extends('layouts.public')
@section('title', $post->title)
@section('description', $post->excerpt ?? Str::limit(strip_tags($post->content), 155))

@if($post->featured_image)
@section('og-image')
<meta property="og:image" content="{{ asset('/storage/' . $post->featured_image) }}">
<meta property="twitter:image" content="{{ asset('storage/' . $post->featured_image) }}">
@endsection
@endif

@section('content')
    <!-- Single Post View (Public) -->
<article class="max-w-4xl mx-auto">
    <!--Post Header-->
    <header class="mb-8">
        <h1 class="text-2xl sm:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{$post->title}}</h1>

        <!-- Line 1: Author & Date -->
        <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
            By {{$post->user?->name ?? "Unknown"}} • {{ optional($post->published_at)->format('M d, Y')}}
        </div>
        <!-- Line 2: Stats & Category -->
         <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500 dark:text-gray-400 mb-4">
            <span class="flex items-center gap-1">
            <!-- Clock icon for reading time -->
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ $post->reading_time }}
</span>
            <span class="flex items-center gap-1">
        <!-- Eye icon for views -->
         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>
        {{ $post->formatted_views }} views
</span>
            @if($post->category)
                <a href="{{route('categories.show', $post->category->slug)}}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                    {{$post->category->name}}
                </a>
                @endif
        </div>

        @if($post->tags->count() > 0)
            <div class="flex flex-wrap gap-2">
                @foreach($post->tags as $tag)
                <a href="{{route('tags.show', $tag->slug)}}" class="bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-gray-200 px-3 py-1 rounded-full text-sm hover:bg-gray-300 dark:hover:bg-slate-600">
                    <!-- Displays clickable tags (like #Laravel, #PHP)-->
                    #{{$tag->name}}
                </a>
                @endforeach
            </div>
            @endif
    </header>

    <!-- Featured Image -->
     @if($post->featured_image)
        <div class="mb-8 max-w-3xl mx-auto">
            <div class="aspect-[16/9] overflow-hidden rounded-xl border border-gray-200 dark:border-slate-700">
            <img src="{{ asset('storage/' . $post->featured_image) }}"
            alt="{{ $post->title }}"
            class="w-full h-full object-cover">
        </div>
    </div>
    @endif

    
    <div 
    x-data="{
        startTime: Date.now(),
        maxScroll:0,
        sent: false
}"
x-init="
    // Track scroll depth
    window.addEventListener('scroll', () => {
        const scrollPercent = Math.round(
        (window.scrollY + window.innerHeight) / document.body.scrollHeight * 100
        );
        maxScroll = Math.max(maxScroll, scrollPercent);
});

        // Send engagement every 15s while page is visible
        const sendEngagement = () => {
            fetch('{{ route('analytics.track', $post) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
},
                body: JSON.stringify({
                    time_spent: Math.round((Date.now() - startTime) / 1000),
                    scroll_depth: maxScroll
}),
            keepalive: true
});
};

        const intervalId = setInterval(() => {
            if (document.visibilityState === 'visible') {
                sendEngagement();
}
}, 15000);

        // Use sendBeacon for reliable data sending on page unload
        const sendBeaconData = () => {
            if (sent) return;
            sent  = true;
            clearInterval(intervalId);

            const data = new FormData();
            data.append('time_spent', Math.round((Date.now() - startTime) / 1000));
            data.append('scroll_depth', maxScroll);
            data.append('_token', '{{ csrf_token() }}');

            navigator.sendBeacon('{{ route('analytics.track', $post) }}', data);
};

        // Send data when user leaves (multiple events for reliability)
        window.addEventListener('beforeunload', sendBeaconData);
        window.addEventListener('pagehide', sendBeaconData);
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'hidden') {
                sendBeaconData();
}
});
"
>


    <!-- Post Content -->
    <div class="post-prose max-w-3xl mx-auto mb-12 break-words">
        {!! $post->content !!}
    <!-- Converts the post's content(written in Markdown) into HTMl.
        Problem Solved: Authors can write in simple Markdown, but readers can see nicely formatted text.
-->
    </div>

<!-- Share Buttons -->
 <x-share-buttons
    :url="route('posts.public.show', $post->slug)"
    :title="$post->title"
    :description="$post->excerpt ?? Str::limit(strip_tags($post->content), 150)"
    />

</div>
    <hr class="my-12">

    <!-- Comments Section -->
    <section class="mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">
            Comments ({{$post->approvedComments->count()}})
        </h2>

    <!-- Comment Form Component -->
     <x-comment-form :post="$post" class="mb-8"/>

    <!-- Display Comments -->
        @if($post->approvedComments->count() > 0)
            <div class="space-y-6">
                @foreach($post->approvedComments as $comment)
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center mb-2">
                            <div class="font-semibold text-gray-900">{{$comment->name}}</div>
                            <span class="mx-2 text-gray-400">•</span>
                            <div class="text-sm text-gray-600">{{$comment->created_at->diffForHumans()}}</div>
                            <!--diffforhmns() is a Laravel Carbon Method(Carbon is the date/time library Laravel uses), it takes a date/time and converts into human-friendly string -->
                        </div>

                        <p class="text-gray-700">{{$comment->content}}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-8">No comments yet. Be the first to comment!</p>
        @endif
    </section>
    <!-- Structured Data (JSON-LD) for Rich Snippets -->
                    <script type="application/ld+json">
                        {
                            "@@context": "https://schema.org",
                            "@@type": "BlogPosting",
                            "headline": "{{ $post->title }}",
                            "description": "{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 155) }}",
                            "author": {
                                "@type": "Person",
                                "name": "{{ $post->user?->name ?? 'Unknown' }}"
                            },
                            "datePublished": "{{ $post->published_at?->toIso8601String() }}",
                            "dateModified": "{{ $post->updated_at->toIso8601String() }}"
                            @if($post->featured_image)
                            ,
                            "image": "{{ asset('storage/'. $post->featured_image) }}"
                            @endif
                        }
                    </script>
                    <!-- Related Posts -->
                    <x-related-posts :post="$post" />
</article>
    @endsection






























