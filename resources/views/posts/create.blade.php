@extends('layouts.admin')
@section('title', 'Create Post')

@section('content')
    <!-- Create Post view form in Admin panel -->
    <div class="bg-white shadow rounded-lg max-w-4xl mx-auto dark:bg-slate-900 dark:border dark:border-slate-700/60">
        <div class="p-6 border-b dark:border-slate-700/60">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Create New Post</h2>
        </div>

        <form action="{{route('posts.store')}}" method="POST" class="p-6" enctype="multipart/form-data">
            @csrf

        <!-- AI Generate Section -->
         <div class="mb-5 p-4 bg-slate-50 rounded-lg border border-indigo-200 dark:bg-slate-800/60 dark:border-slate-700"
         
            x-data="{
                loading: false,
                showForm: false,
                topic: '',
                tone: 'professional',
                length: 'medium',
                async generate() {
                if (!this.topic.trim()) {
                    alert('Please enter a topic.');
                    return;
}
                    this.loading = true;
                    try {
                        const response = await fetch('{{ route('ai.generate-post') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
},
                            body: JSON.stringify({
                                topic: this.topic,
                                tone: this.tone,
                                length: this.length
}) 
});
                        const data = await response.json();
                        if (data.success) {
                            document.querySelector('input[name=title]').value = data.data.title;
                            document.querySelector('textarea[name=excerpt]').value = data.data.excerpt;
                            if (window.tiptapEditor) {
                                window.tiptapEditor.commands.setContent(data.data.content);
                                document.getElementById('content-textarea').value = data.data.content;

}
                            this.showForm = false;
                            this.topic = '';
} else {
                            alert(data.error || 'Failed to generate content.');
}
} catch (error) {
                        alert('Error: ' + error.message);
} finally {
                        this.loading = false;
}
}
}">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
</svg>
<span class="font-medium text-indigo-900 dark:text-indigo-200">AI Post Generator</span>
</div>
                <x-primary-button type="button"
                @click="showForm = !showForm"
                x-on:click="$el.blur()">
                    <span x-text="showForm ? 'Close ': 'Generate with AI'"></span>
</x-primary-button>
</div>

<div x-show="showForm"  class="mt-4 space-y-3">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">What should the post be about?</label>
        <input type="text"
                x-model="topic"
                placeholder="e.g., 10 Tips for Better Sleep, How to Start a Garden..."
                class="h-10 w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100">
</div>
<div class="grid grid-cols-2 gap-3">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Tone</label>
        <select x-model="tone" class="h-10 w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100">
            <option value="professional">Professional</option>
            <option value="casual">Casual</option>
            <option value="friendly">Friendly</option>
            <option value="formal">Formal</option>
            <option value="humorous">Humorous</option>
</select>
</div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Length</label>
        <select x-model="length" class="h-10 w-full border-gray-300 rounded-md shadow-sm text-sm dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100">
            <option value="short">Short (300-500 words)</option>
            <option value="medium">Medium (600-900 words)</option>
            <option value="long">Long (1000-1500 words)</option>
</select>
</div>
</div>
    <x-primary-button type="button"
            class="w-full"
            @click="generate()"
            x-bind:disabled="loading"
            x-on:click="$el.blur()">
            <span x-text="loading ? 'Generating...' : 'Generate Post'"></span>
</x-primary-button>
</div>
</div>
         
            



        <!-- Autosave Component -->
        <x-autosave :draft-key="'draft_create_' . auth()->id()" />

        
        <div class="mb-5">
            <label for="title" class="flex items-center justify-between text-sm font-medium text-gray-700 mb-2">
                <span>Title *</span>
                <span id="title-count" class="text-xs text-gray-500">0/70</span>
                </label>
            <input type="text" id="title" name="title" value="{{old('title')}}" required
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                   <p class="text-xs text-gray-500 mt-1">Recommended 50-70 characters.</p>
        </div>
        <div class="mb-5">
            <label for="excerpt" class="flex items-center justify-between text-sm font-medium text-gray-700 mb-2">
            <span>Excerpt</span>
            <span id="excerpt-count" class="text-xs text-gray-500">0/160</span>
            </label>
            <textarea name="excerpt" id="excerpt" rows="3"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{old('excerpt')}}</textarea>
            <p class="text-xs text-gray-500 mt-1">Recommended 120-160 characters.</p>
        </div>
        <div class="mb-5">
            <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
            <input 
                type="file"
                id="featured_image"
                name="featured_image"
                accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                class="block w-full text-sm file:mr-4 file:rounded-md file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-200
                border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                @error('featured_image') border-red-500 rounded-md p-1 @enderror"
               /> 
            
            <p  class="text-sm text-gray-500 mt-2">Max 2MB. Formats: JPEG, PNG, GIF, WebP</p>
            <p class="text-xs text-gray-500">Featured image appears at the top of the post.</p>
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
<span class="mx-1 h-6 w-px bg-gray-200 self-center" aria-hidden="true"></span>
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
        >{{ old('content') }}</textarea>

        @error('content')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category_id" id="category_id"
                            class="w-full h-10 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}" {{old('category_id') == $category->id ? 'selected' : ''}}>
                                {{$category->name}}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" required
                            class="w-full h-10 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="draft" {{old('status') == 'draft' ? 'selected' : ''}}>Draft</option>
                            <option value="published" {{old('status') == 'published' ? 'selected' : ''}}>Published</option>
                    </select>
                    </div>
</div>

         <div class="mb-4 rounded-md border border-gray-100 bg-gray-50/50 p-3 dark:border-gray-700 dark:bg-gray-800/60">
             <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-100">Tags</label>
             <p class="text-xs text-gray-500 mb-2 dark:text-gray-400">Optional</p>
             <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                 @foreach($tags as $tag)
                     <label class="inline-flex items-center gap-2">
                         <input type="checkbox" name="tags[]" value="{{$tag->id}}"
                                {{in_array($tag->id, old('tags', [])) ? 'checked' : ''}}
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800">
                         <span class="text-sm leading-none text-gray-700 dark:text-gray-200">{{$tag->name}}</span>
                     </label>
                     @endforeach
             </div>
         </div>
                
                    <div class="flex justify-end gap-3 mt-6">
             <button type="submit" class="inline-flex items-center justify-center h-10 min-w-[140px] px-5 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                 Create Post
             </button>
             <a href="{{route('posts.index')}}" class="inline-flex items-center justify-center h-10 min-w-[140px] px-5 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                 Cancel
             </a>
         </div>
    </div>
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
            button.addEventListener('click', ()=> {
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
                        editor.chain().focus().toggleHeading({ level: parseInt(level) }).run()
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
                            editor.chain().focus().setLink({ href: url }).run()
                        }
                        break
                    case 'image':
                        const imageUrl = prompt('Enter image URL:')
                        if (imageUrl) {
                            editor.chain().focus().setImage({src: imageUrl}).run()
                        }
                        break
                    case 'table':
                        editor.chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true}).run()
                        break
                }

                button.classList.toggle('is-active', editor.isActive(action, level ? {level: parseInt(level)} : {}))
            })

        })
    })
</script>

<script>
        const titleInput = document.getElementById('title');
        const excerptInput = document.getElementById('excerpt');
        const titleCount = document.getElementById('title-count');
        const excerptCount = document.getElementById('excerpt-count');

        const updateCounts = () => {
            if (titleInput && titleCount) {
                titleCount.textContent = `${titleInput.value.length}/70`;
            }
            if (excerptInput && excerptCount) {
                excerptCount.textContent = `${excerptInput.value.length}/160`;
            }
        };

        if (titleInput || excerptInput) {
            updateCounts();
            titleInput && titleInput.addEventListener('input', updateCounts);
            excerptInput && excerptInput.addEventListener('input', updateCounts);
        }
        </script>
        </form>
    </div>
@endsection


