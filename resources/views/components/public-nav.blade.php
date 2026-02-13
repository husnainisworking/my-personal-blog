<nav class="bg-white shadow dark:bg-slate-900 dark:border-b dark:border-slate-700">
    <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8 ">
        <div class="flex flex-col gap-3 py-3 sm:flex-row sm:items-center sm:gap-6">
            <div class="flex min-w-0 justify-center sm:justify-start sm:flex-nowrap">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}"
                        class="text-lg sm:text-2xl font-bold text-indigo-600 dark:text-indigo-400 truncate max-w-[11rem] sm:max-w-none text-center">
                        My Personal Blog
                    </a>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="{{ route('home') }}"
                        class="border-transparent text-gray-600 hover:text-gray-900 inline-flex items-center px-1 py-2 text-sm font-medium transition-colors duration-150 dark:text-gray-300 dark:hover:text-white">
                        Home
                    </a>
                    <a href="{{ route('public.categories.index') }}"
                        class="border-transparent text-gray-600 hover:text-gray-900 inline-flex items-center px-1 py-2 text-sm font-medium transition-colors duration-150 dark:text-gray-300 dark:hover:text-white"
                        title="Browse posts by topic (e.g., Technology, Lifestyle)">
                        Categories
                    </a>
                    <a href="{{ route('public.tags.index') }}"
                        class="border-transparent text-gray-600 hover:text-gray-900 inline-flex items-center px-1 py-2 text-sm font-medium transition-colors duration-150 dark:text-gray-300 dark:hover:text-white"
                        title="Browse posts by keyword (e.g., #laravel, #tips)">
                        Tags
                    </a>
                </div>
            </div>
            <div class="flex flex-col w-full sm:w-auto sm:flex-row sm:items-center sm:gap-4 sm:ml-6">
                <form action="{{ route('search') }}" method="GET" class="flex w-full sm:w-auto sm:ml-4 relative rounded-md hover:shadow-[0_0_30px_rgba(129,140,248,0.6)] focus-within:shadow-[0_0_30px_rgba(129,140,248,0.6)] transition-shadow duration-300"
                    x-data="{ searching: false , query: '{{ request('q') }}' }" @submit="searching = true">
                    <input type="text" name="q" placeholder="Search posts, tags, or topics…"
                        class="h-10 w-full sm:w-48 lg:w-64 xl:w-72 rounded-l-md border border-gray-300 dark:border-slate-700 border-r-0 pr-8 pl-4 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:text-gray-100 dark:placeholder-gray-400"
                        x-model="query" required minlength="2" x-ref="searchInput">
                        <button type="button" class="absolute right-12 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                            x-show="query.length > 0" x-cloak @click="query = ''; $refs.searchInput.focus()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <button type="submit"
                        class="h-10 rounded-r-md bg-indigo-600 text-white px-3 text-sm hover:bg-indigo-700 shrink-0 flex items-center justify-center focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-slate-900"
                        :disabled="searching">
                        <svg x-show="!searching" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <svg x-show="searching" x-cloak class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </form>
                <button type="button"
                    
                    class="hidden sm:inline-flex items-center justify-center gap-2 w-10 h-10 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-colors duration-150 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:hover:bg-slate-700"
                    x-data @click="$store.theme.toggle()"
                    title="Toggle dark mode">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M21.64 13a1 1 0 0 0-1.05-.14A8 8 0 0 1 11.14 3.4a1 1 0 0 0-1.19-1.19A10 10 0 1 0 22 14.05a1 1 0 0 0-.36-1.05Z" />
                    </svg>
                    </button>
                    @guest
                    <div class="flex items-center gap-3 mt-3 sm:mt-0 sm:ml-4">
                        <a href="{{ route('register') }}"
                            class="text-gray-600 hover:text-gray-900 text-sm font-medium dark:text-gray-300 dark:hover:text-white">Create
                            account</a>
                        <a href="{{ route('login') }}"
                            class="text-gray-600 hover:text-gray-900 text-sm font-medium dark:text-gray-300 dark:hover:text-white">Log
                            in</a>
                    </div>
                @endguest
                @auth
                    <div class="flex flex-row items-center gap-3 mt-3 sm:mt-0 sm:ml-4">
                        <a href="{{ route('posts.create') }}"
                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition-colors duration-150 whitespace-nowrap">
                            New Post</a>
                        
                        <!-- User Dropdown -->
                        <div class="relative" x-data="{open: false}" @mouseenter="open =  true" @mouseleave="open = false" >
                         <button @click="open = !open" @click.outside="open = false" 
                            class="inline-flex group items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors duration-150 dark:bg-slate-800 dark:text-gray-200 dark:border-slate-600 dark:hover:bg-slate-700">
                            <svg class="w-5 h-5 fill-none group-hover:fill-indigo-100 stroke-gray-600 group-hover:stroke-indigo-600 dark:stroke-gray-300 dark:group-hover:fill-indigo-900 dark:group-hover:stroke-indigo-400" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <svg class="w-4 h-4 transition-transform fill-none" :class="{ 'rotate-180': open }" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                         </button>

                         <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 py-1 z-50 dark:bg-slate-800 dark:border-slate-700">

                         <a href="{{ route('posts.index') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-slate-700">
                            My Posts
                        </a>

                        @if (auth()->user()->hasRole('admin'))
                            <a href="{{ route('dashboard') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-slate-700">
                                Admin Dashboard
                            </a>
                        @endif

                        <div class="border-t border-gray-200 dark:border-slate-700 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-slate-700">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
