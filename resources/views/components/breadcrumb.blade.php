@props(['items'])

<nav aria-label="Breadcrumb" class="mb-6 text-sm">
    <ol class="flex items-center flex-wrap gap-1 text-gray-500 dark:text-gray-400">
        @foreach($items as $item)
            @if(!$loop->last)
                <li class="flex items-center gap-1">
                    <a href="{{ $item['url'] }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            {{ $item['label'] }}
                    </a>
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </li>
            @else
                <li class="text-gray-900 dark:text-gray-100 font-medium truncate max-w-[200px] sm:max-w-xs">
                    {{ $item['label'] }}
                </li>
            @endif
        @endforeach
    </ol>
</nav>