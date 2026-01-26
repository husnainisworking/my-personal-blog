@extends('layouts.admin')
@section('title', 'Edit Post')
@section('content')
    <div class="bg-white shadow rounded-lg max-w-4xl mx-auto">
        <div class="p-6 border-b">
            <h2 class="text-2xl font-bold text-gray-800">Edit Post</h2>
        </div>

        <form action="{{route('posts.update', $post)}}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

        <!-- Autosave Component -->
        <x-autosave :post-id="$post->id" :draft-key="'draft_edit_' . $post->id" />


        <div class="mb-5">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
            <input type="text" name="title" id="title" value="{{old('title',$post->title)}}" required
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
            <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
            <textarea name="excerpt" id="excerpt" rows="3"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{old('excerpt', $post->excerpt)}}</textarea>
        </div>

        <!-- Tiptap editor -->
         <div class="mb-5">
            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                Content *
</label>

<div class="tiptap-editor">
<!-- Toolbar -->
<div class="tiptap-toolbar flex flex-wrap gap-2 rounded-md border border-gray-200 bg-gray-50 p-2" id="tiptap-toolbar">
    <button type="button" data-action="bold" title="Bold (Ctrl+B)">
        <strong>B</strong>
</button>
    <button type="button" data-action="italic" title="Italic (Ctrl+I)">
        <em>I</em>
</button>
<button type="button" data-action="strike" title="Strikethrough">
    <s>S</s>
</button>
<span class="mx-1 h-6 w-px  bg-gray-200 self-center" aria-hidden="true"></span>
<button type="button" data-action="heading" data-level="1" title="Heading 1">
    H1
</button>
    <button type="button" data-action="heading" data-level="2" title="Heading 2">
        H2
</button>
<button type="button" data-action="heading" data-level="3" title="Heading 3">
    H3
</button>
<span class="mx-1 h-6 w-px bg-gray-200 self-center" aria-hidden="true"></span>
<button type="button" data-action="bulletList" title="Bullet List">
        • List
</button>
<button type="button" data-action="orderedList" title="Numbered List">
        1. List
</button>
<button type="button" data-action="codeBlock" title="Code Block">
     &lt;/&gt;
</button>
<span class="mx-1 h-6 w-px bg-gray-200 self-center" aria-hidden="true"></span>
<button type="button" data-action="link" title="Add Link">
    Link
</button>
<button type="button" data-action="image" title="Add Image">
    Image
</button>
<button type="button" data-action="table" title="Insert Table">
    Table
</button>
</div>

<!-- Editor -->
        <div id="tiptap-content"></div>
</div>

<!-- Hidden textarea -->
<textarea 
        name="content"
        id="content-textarea"
        class="hidden"
        required
        >{{ old('content', $post->content) }}</textarea>

        @error('content')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
</div>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const initialContent = document.getElementById('content-textarea').value
            const editor = window.initTiptapEditor(
                document.getElementById('tiptap-content'),
                initialContent
            )
                window.tiptapEditor = editor

            editor.on('update', ({ editor }) => {
                document.getElementById('content-textarea').value = editor.getHTML()
            })

            document.querySelectorAll('#tiptap-toolbar button').forEach(button => {
                button.addEventListener('click', () => {
                    const action = button.dataset.action
                    const level = button.dataset.level

                    switch(action) {
                        case 'bold':
                            editor.chain().focus().toggleBold().run()
                            break
                        case 'italic':
                            editor.chain().focus().toggleItalic().run()
                            break
                        case 'strike':
                            editor.chain().focus().toggleStrike().run()
                            break
                        case 'heading':
                            editor.chain().focus().toggleHeading({level: parseInt(level)}).run()
                            break
                        case 'bulletList':
                            editor.chain().focus().toggleBulletList().run()
                            break
                        case 'orderedList':
                            editor.chain().focus().toggleOrderedList().run()
                            break
                        case 'codeBlock':
                            editor.chain().focus().toggleCodeBlock().run()
                            break
                        case 'link':
                            const url = prompt('Enter URL:')
                            if(url) {
                                editor.chain().focus().setLink({href: url}).run()
                            }
                            break
                        case 'image':
                            const imageUrl = prompt('Enter image URL:')
                            if( imageUrl ) {
                            editor.chain().focus().setImage({src: imageUrl}).run()
                            }
                            break
                        case 'table':
                            editor.chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run()
                            break
                    } 

                    button.classList.toggle('is-active', editor.isActive(action, level ? {level: parseInt(level)} : {}))
                })
            })
        })
        </script>



        <div class="mb-5">
            <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                Featured image
        </label>

        <!-- Show current image if exists -->
        @if(isset($post) && $post->featured_image)
        <div class="mb-4">
                <img src="{{ asset('storage/' . $post->featured_image) }}"
                alt="Current featured image"
                class="w-full max-w-md rounded-md border border-gray-200"
                >

                <label class="mt-2 inline-flex items-center gap-2">
                    <input type="checkbox" 
                        id="remove_featured_image" 
                        name="remove_featured_image" 
                        value="1"
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                        >
                    <span class="text-sm text-gray-700 leading-none">
                        Remove current image</span>
        </label>    
    </div>
        @endif

        <!-- New image upload field -->
        <input type="file"
                name="featured_image"
                id="featured_image"
                accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                class="block w-full text-sm file:mr-4 file:rounded-md file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-200 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                     @error('featured_image') border-red-500 @enderror">

        <!-- Error message for featured_image -->
        @error('featured_image')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

        <!-- Helper text -->        
        <small class="block mt-2 text-gray-500">
            Max 2MB. Formats: JPEG, PNG, GIF, WebP
        </small>
        
</div>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select name="category_id" id="category_id"
                        class="w-full h-10 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{$category->id}}" {{old('category_id', $post->category_id) === $category->id ? 'selected' : ''}}>
                            {{$category->name}}
                        </option>
                        @endforeach
                </select>
            </div>

             <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" required
                        class="w-full h-10 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="draft" {{old('status', $post->status) == 'draft' ? 'selected' : ''}}>Draft</option>
                        <option value="published" {{old('status', $post->status) == 'published' ? 'selected' : ''}}>Published</option>
    </select>
    </div>
    </div>

            <div class="mb-4 rounded-md border border-gray-100 bg-gray-50/50 p-3 dark:border-slate-700 dark:bg-slate-800/60">
               <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-100">Tags</label>
               <p class="text-xs text-gray-500 mb-2 dark:text-gray-400">Optional</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    @foreach($tags as $tag)
                         <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                        {{ in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray())) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800">   
                <span class="text-sm leading-none text-gray-700 dark:text-gray-200">{{$tag->name}}</span>
                </label>
                    @endforeach
                </div>
            </div>
       
        
               
    <div class="flex justify-end gap-3 mt-6">
    <button type="submit" class="inline-flex items-center justify-center h-10 min-w-[140px] px-5 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
        Update Post
    </button>

    <a href="{{ route('posts.index')}}" class="inline-flex items-center justify-center h-10 min-w-[140px] px-5 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
        Cancel
    </a>
    </div>
    </div>
    </div>
     </div>
        </form>
    </div>
@endsection 





