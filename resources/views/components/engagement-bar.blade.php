@props([
    'likes' => null,
    'comments' => null,
    'views' => null,
    'readTime' => null,
    'url' => null,
    'title' => null,        
])

<div class="max-w-3xl mx-auto mb-12" x-data="{ showToast: false}">
    <!-- Toast Notification -->
     <div
        x-show="showToast"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 px-4 py-2 rounded-lg shadow-lg text-sm font-medium z-50"
        >
        Link copied to clipboard!
</div>
    <div class="flex flex-wrap items-center justify-between gap-4 border-y border-gray-200 dark:border-slate-700 py-3 text-sm text-gray-600 dark:text-gray-300">
        <div class="flex items-center gap-5">
            @if($likes !== null)
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span>{{$likes}}</span>
                </div>
            @endif

            @if($comments !== null)
                <a href="#comments" class="flex items-center gap-2 hover:text-gray-900 dark:hover:text-gray-100">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                    <span>{{$comments}}</span>
                </a>
            @endif
            
            @if($views !== null)
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span>{{$views}}</span>
                </div>
            @endif

            @if($readTime !== null)
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{$readTime}}</span>
                </div>
            @endif
        </div>
        
        <button
            type="button"
            class="p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800"
            title="Share"
            @click="
                if(navigator.share) {
                    navigator.share({title: '{{ addslashes($title ?? '')}}', url: '{{ $url ?? request()->url() }}'})
                    }  else {
                        navigator.clipboard.writeText('{{ $url ?? request()->url() }}');
                        showToast = true;
                        setTimeout(() => showToast = false, 2000);
                        }
                "
            >
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
        </button>
    </div>
</div>