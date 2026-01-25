<nav class="bg-white shadow dark:bg-slate-900 dark:border-b dark:border-slate-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                My Personal Blog
            </a>
            <button type="button"
                class="inline-flex items-center justify-center w-10 h-10 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-colors duration-150 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:hover:bg-slate-700"
                x-data @click="$store.theme.toggle()"
                title="Toggle dark mode">
                  <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M21.64 13a1 1 0 0 0-1.05-.14A8 8 0 0 1 11.14 3.4a1 1 0 0 0-1.19-1.19A10 10 0 1 0 22 14.05a1 1 0 0 0-.36-1.05Z" />
                  </svg>
            </button>
        </div>
    </div>
</nav>
