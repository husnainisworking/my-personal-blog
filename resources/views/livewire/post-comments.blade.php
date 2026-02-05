<section class="mb-12" id="comments">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
        Comments ({{ $comments->count() }})
    </h2>

    <!-- Success Message -->
     @if($successMessage)
     <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 4000)"
        class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded"
    >
        {{ $successMessage }}
    </div>
    @endif

    <!-- Comment form -->
     <div class="bg-gray-50 dark:bg-slate-800 p-6 rounded-lg mb-8">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Leave a comment</h3>


        <form wire:submit="addComment" wire:key="comment-form-{{ $formKey }}" autocomplete="off">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="name"
                        id="name"
                        class="w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror"
                    >
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input
                    type="email"
                    wire:model="email"
                    id="email"
                    class="w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-500 @enderror"
                >
                @error('email')
                    <p class="text-red-500 text-s mt-1">{{ $message }}</p>
                @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Comment <span class="text-red-500">*</span>
                </label>
                <textarea
                    wire:model="content"
                    id="content"
                    rows="4"
                    class="w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder:text-gray-400 @error('content') border-red-500 @enderror"
                    placeholder="What are your thoughts?"
                    ></textarea>
                    @error('content')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition-colors duration-200 disabled:opacity-50"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>Post Comment</span>
                    <span wire:loading>Posting ...</span>
                </button>
            </form>
        </div>

        <!-- Comments List -->
         @if($comments->count() > 0)
            <div class="space-y-6">
                @foreach($comments as $comment)
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow" wire:key="comment-{{ $comment->id }}">

                        @if($editingCommentId === $comment->id)
                        <!-- Edit mode -->
                         <div>
                            <textarea
                                    wire:model="editContent"
                                    rows="3"
                                    class="w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mb-3"
                                    ></textarea>
                                    @error('editContent')
                                        <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
                                    @enderror
                                    <div class="flex gap-2">
                                        <button
                                            wire:click="updateComment"
                                            class="bg-indigo-600 text-white px-4 py-1 rounded text-sm hover:bg-indigo-700"
                                            >
                                                Save
                                        </button>
                                        <button
                                            wire:click="cancelEditing"
                                            class="bg-gray-300 dark:bg-slate-600 text-gray-700 dark:text-gray-200 px-4 py-1 rounded text-sm hover:bg-gray-400 dark:hover:bg-slate-500"
                                            >
                                                Cancel
                                        </button>
                                    </div>
                                </div>
                            @else
                            <!-- Display Mode -->
                             <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <div class="font-semibold text-gray-900 dark:text-white">{{ $comment->name }}</div>
                                    <span class="mx-2 text-gray-400">•</span>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</div>
                                </div>

                                <!-- Admin Actions (only show if user is admin) -->
                                 @auth
                                    @can('approve', $comment)
                                    <div class="flex gap-2">
                                        <button
                                            wire:click="startEditing({{ $comment->id }})"
                                            class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800"
                                        >
                                            Edit
                                        </button>
                                        <button
                                                wire:click="deleteComment({{ $comment->id }})"
                                                wire:confirm="Are you sure you want to delete this comment?"
                                                class="text-sm text-red-600 dark:text-red-400 hover:text-red-800"
                                            >
                                                Delete
                                        </button>
                                    </div>
                                    @endcan
                                @endauth
                            </div>

                            <p class="text-gray-700 dark:text-gray-300">{{ $comment->content }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">No comments yet. Be the first to comment!</p>
            @endif
        </section>
