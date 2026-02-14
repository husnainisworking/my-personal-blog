{{-- resources/views/categories/show.blade.php --}}
@extends('layouts.public')

@section('title', $category->name)

@section('content')
        <div class="mb-8">
            <h1 class="text-2xl sm:text-4xl  mb-2">
                <span class="text-gray-500 dark:text-gray-400 font-normal">Posts in</span>
                <span class="text-gray-900 dark:text-gray-100 font-bold">{{$category->name}}</span>    
            </h1>
        
            @if($category->description)
                <p class="text-gray-600">{{ $category->description }}</p>
            @endif

            <p class="text-gray-500 text-sm mt-1">Found {{ $posts->total() }} {{ \Illuminate\Support\Str::plural('post', $posts->total()) }}</p>

            <div class="mt-2">
        
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Categories' , 'url' => route('public.categories.index')],
            ['label' =>  $category->name],
        ]" />    

        </div>

            @auth
                @role('admin')
                    <div class="mt-4">
                        <a href="{{ route('categories.edit', $category) }}"
                        class="inline-flex items-center bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Edit category
                    </a>
</div>
@endrole
@endauth
</div>
            @if($posts->count() > 0)
                <div class="grid gap-8">
                    @foreach($posts as $post)
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                            <div class="p-5 sm:p-6">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                                    <a href="{{ route('posts.public.show', $post->slug) }}" class="hover:text-indigo-600 dark:text-indigo-400">
                                        {{ $post->title }}
                                    </a>
                                </h2>

                                <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-gray-600 mb-4">
                                    <span>{{ $post->user?->name ?? 'Unknown'}}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ optional($post->published_at)->format('M d, Y') }}</span>
                                </div>

                                @if($post->excerpt)
                                    <p class="text-gray-700 mb-4">{{$post->excerpt}}</p>
                                @else
                                    <p class="text-gray-700 mb-4">{{ Str::limit(strip_tags($post->content), 200)}}</p>
                                @endif
                                
                                @if($post->tags->count() > 0)
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @foreach($post->tags as $tag)
                                            <a href="{{ route('tags.show', $tag->slug) }}"
                                            class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm hover:bg-gray-300">
                                            #{{ $tag->name }}
                                        </a>
                                        @endforeach
</div>
@endif

<a href="{{ route('posts.public.show', $post->slug) }}"
class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-medium">
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
    <div class="text-center py-12">
        <!-- Empty folder icon -->
    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776" />
    </svg>
    <p class="text-gray-500 text-center py-12">No posts yet in this category.</p>
    </div>
    @endif
@endsection


                                

