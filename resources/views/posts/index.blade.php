@extends('layouts.admin')
@section('title', 'Manage Posts')
@section('content')
    <!-- This Blade file is Posts Management View in Admin Panel-->
    <div class="bg-white shadow rounded-lg">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Manage Posts</h2>
                <a href="{{route('posts.create')}}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Create New Post
                </a>
            </div>
        </div>

        <div class="p-6">
            @if($posts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($posts as $post)
                                <tr>   
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($post->featured_image)
                                            <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                            alt="{{ $post->title }}"
                                            class="h-12 w-12 rounded object-cover">
                                            @else
                                            <div class="h-12 w-12 bg-gray-200 rounded flex items-center justify-center">
                                                <span class="text-gray-400 text-xs">No image</span>
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{$post->title}}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{$post->category?->name ?? 'Uncategorized'}}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <!-- span tag is used to markup part of something -->
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{$post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}}">
                                            {{ucfirst($post->status)}}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{$post->user?->name ?? "Unknown"}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{$post->created_at->format('M d, Y')}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{route('posts.edit', $post)}}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 mr-3">Edit</a>
                                   <x-confirm-modal title="Delete post?" message="This will permanently remove this post and its comments.">
                                        <span class="text-red-600 hover:text-red-900">Delete</span>
                                    </x-confirm-modal>
                                    <form action="{{ route('posts.destroy', $post)}}" method="POST" class="confirm-form hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    </td>
                                </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                {{$posts->links()}}
                    <!-- Renders pagination links automatically (Laravel's built-in paginator).-->
                </div>
                @else
                    <p class="text-gray-500 text-center py-8">No posts yet. Create your first post!</p>
                @endif
        </div>
    </div>
@endsection










