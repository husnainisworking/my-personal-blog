@extends('layouts.admin')
@section('title', 'Manage Comments')
@section('content')
                        <!-- Admin dashboard page for managing comments -->
    <div class="bg-white shadow rounded-lg">
       <div class="p-6 border-b flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-2xl font-bold text-gray-800">
            {{ __('Manage Comments')}}
        </h2>
   <a href="{{ route('comments.trashed') }}"
    class="inline-flex items-center px-4 py-2 rounded-md border border-gray-300 bg-white text-gray-700 font-medium hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-200 dark:hover:bg-slate-700 whitespace-nowrap">
    View Trashed Comments
</a>



    </div>
            <!-- Comments List -->
        <div class="p-6">
            @if($comments->count() > 0)
                <div class="space-y-4">
                    @foreach($comments as $comment)
                        <div class="border rounded-lg p-4 {{$comment->approved ? 'bg-white dark:bg-slate-800' : 'bg-yellow-50 dark:bg-yellow-900/20'}}">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="font-semibold text-gray-900">{{$comment->name}}</span>
                                    <span class="text-gray-600 text-sm ml-2">({{ $comment->email }})</span>
                                    @if(!$comment->approved)
                                        <span class="ml-2 bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 px-2 py-1 rounded text-xs">Pending</span>
                                    @endif
                                </div>
                                <span class="text-sm text-gray-500">{{$comment->created_at->diffForHumans()}}</span>
                            </div>

                            <p class="text-gray-700 mb-2">{{$comment->content}}</p>

                            <div class="text-sm text-gray-600 mb-3">
                                On post: <a href="{{route('posts.public.show', $comment->post->slug)}}" class="text-indigo-600 dark:text-indigo-400 hover:underline" target="_blank">{{$comment->post->title}}</a>
                            </div>
                        <!-- Action Buttons -->
                            <div class="flex space-x-3">
                                @if(!$comment->approved)
                                    <form action="{{route('comments.approve', $comment)}}" method="POST" class="inline">
                                        @csrf
                                    <button type="submit" class="inline-flex items-center h-8 px-3 rounded-md border border-green-600 text-green-600 dark:text-green-400 dark:border-green-500 text-xs font-medium hover:bg-green-50 dark:hover:bg-green-900/30">
                                        Approve
                                    </button>
                                    </form>
                                    @endif
                            <form action="{{route('comments.destroy', $comment)}}" method="POST" class="inline">
                                @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center h-8 px-3 rounded-md bg-red-500 dark:bg-red-600 text-white text-xs font-medium hover:bg-red-600 dark:hover:bg-red-700" onclick="return confirm('Delete this comment?')">
                                Delete
                            </button>
                            </form>
                            </div>
                        </div>
                        @endforeach
                </div>

                <div class="mt-6">
                    {{$comments->links()}}
                </div>
                @else
                    <p class="text-gray-500 text-center py-8">No comments yet.</p>
                @endif
        </div>
    </div>
    @endsection






















