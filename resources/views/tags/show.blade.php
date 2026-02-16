@extends('layouts.public')
@section('title' , '#'.$tag->name)
@section('content')
    <!-- Public-facing page for a tag -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-4xl mb-2">
            <span class="text-gray-500 dark:text-gray-400 font-normal">Found <span class="text-gray-900 dark:text-gray-100 font-bold"> {{ $posts->total() }} </span> {{ \Illuminate\Support\Str::plural('post', $posts->total()) }} tagged</span>
            <span class="text-gray-900 dark:text-gray-100 font-bold">#{{$tag->name}}</span>
        </h1>

        <div class="mt-2">
            <x-breadcrumb :items="[
            ['label' => 'Home' , 'url' => route('home')],
            ['label' => 'Tags', 'url' => route('public.tags.index')],
            ['label' => '#' . $tag->name],
        ]" />
</div>
</div>


    @if($posts->count() > 0)
        <div class="grid gap-8">
            @foreach($posts as $post)
                <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">
                            <a href="{{route('posts.public.show', $post->slug)}}" class="hover:text-indigo-600 dark:text-indigo-400">
                                {{$post->title}}
                            </a>
                        </h2>

                        <div class="flex items-center text-sm text-gray-600 mb-4">
                            <span>{{$post->user?->name ?? "Unknown"}}</span>
                            <span class="mx-2">•</span>
                            <span>{{ optional($post->published_at)->format('M d, Y') }}</span>
                            @if($post->category)
                                <span class="mx-2">•</span>
                                <a href="{{route('categories.show', $post->category->slug)}}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                                    {{$post->category->name}}
                                </a>
                                @endif
                        </div>

                        @if($post->excerpt)
                            <p class="text-gray-700 mb-4">{{$post->excerpt}}</p>
                        @else
                            <p class="text-gray-700 mb-4">{{ Str::limit(strip_tags($post->content), 200) }}</p>
                        @endif

                    <a href="{{route('posts.public.show', $post->slug)}}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-medium">
                        Read more →
                    </a>
                    </div>
                </article>
                @endforeach
        </div>

        <div class="mt-8">
            {{$posts->links()}}
        </div>
    @else
    <div class="text-center py-12">
        <!-- Empty tag icon -->
         <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
        </svg>
    <p class="text-gray-500 text-center py-12">No posts with this tag yet.</p>
</div>
    @endif
    @endsection

