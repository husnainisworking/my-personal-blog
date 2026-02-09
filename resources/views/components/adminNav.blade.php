<nav class="bg-white shadow-lg dark:bg-slate-900 dark:border-b dark:border-slate-700" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                        My Personal Blog
                    </a>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    @can('view dashboard')
                        <a href="{{ route('dashboard') }}"
                            class="border-transparent text-gray-600 hover:border-gray-300 hover:text-gray-900 inline-flex items-center px-2 py-2 border-b-2 text-sm font-medium transition duration-150 ease-in-out dark:text-gray-300 dark:hover:text-white dark:hover:border-slate-600">
                            Dashboard
                        </a>
                    @endcan

                    @can('view posts')
                        <a href="{{ route('posts.index') }}"
                            class="border-transparent text-gray-600 hover:border-gray-300 hover:text-gray-900 inline-flex items-center px-2 py-2 border-b-2 text-sm font-medium transition duration-150 ease-in-out dark:text-gray-300 dark:hover:text-white dark:hover:border-slate-600">
                            Posts
                        </a>
                    @endcan

                    @can('view dashboard')
                        <a href="{{ route('categories.index') }}"
                            class="border-transparent text-gray-600 hover:border-gray-300 hover:text-gray-900 inline-flex items-center px-2 py-2 border-b-2 text-sm font-medium transition duration-150 ease-in-out dark:text-gray-300 dark:hover:text-white dark:hover:border-slate-600">
                            Categories
                        </a>
                    @endcan

                    @can('view dashboard')
                        <a href="{{ route('tags.index') }}"
                            class="border-transparent text-gray-600 hover:border-gray-300 hover:text-gray-900 inline-flex items-center px-2 py-2 border-b-2 text-sm font-medium transition duration-150 ease-in-out dark:text-gray-300 dark:hover:text-white dark:hover:border-slate-600">
                            Tags
                        </a>
                    @endcan


                    @can('view dashboard')
                        <a href="{{ route('admin.analytics') }}"
                            class="border-transparent text-gray-600 hover:border-gray-300 hover:text-gray-900 inline-flex items-center px-2 py-2 border-b-2 text-sm font-medium transition duration-150 ease-in-out dark:text-gray-300 dark:hover:text-white dark:hover:border-slate-600">
                            Analytics
                        </a>
                    @endcan

                    @can('view dashboard')
                        <a href="{{ route('comments.index') }}"
                            class="border-transparent text-gray-600 hover:border-gray-300 hover:text-gray-900 inline-flex items-center px-2 py-2 border-b-2 text-sm font-medium transition duration-150 ease-in-out dark:text-gray-300 dark:hover:text-white dark:hover:border-slate-600">
                            Comments
                        </a>
                    @endcan
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <button type="button"
                    class="hidden sm:inline-flex items-center justify-center w-10 h-10 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-colors duration-150 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:hover:bg-slate-700"
                    x-data @click="$store.theme.toggle()"
                    title="Toggle dark mode">
                     <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M21.64 13a1 1 0 0 0-1.05-.14A8 8 0 0 1 11.14 3.4a1 1 0 0 0-1.19-1.19A10 10 0 1 0 22 14.05a1 1 0 0 0-.36-1.05Z" />
                    </svg>
                </button>
                <a href="{{ route('home') }}" target="_blank"
                    class="hidden sm:inline-flex items-center h-9 px-3 rounded-md text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-slate-800">
                    View Site
                </a>
                <form class="hidden sm:inline-flex" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center h-9 px-3 rounded-md text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-slate-800">
                        Logout
                    </button>
                </form>

                <button @click="open = !open"
                    class="sm:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-800">
                    <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="open" class="sm:hidden" x-cloak>
        <div class="pt-2 pb-3 space-y-1">
            @can('view dashboard')
                <a href="{{ route('dashboard') }}"
                    class="block w-full text-left px-4 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-800">
                    Dashboard
                </a>
            @endcan

            @can('view posts')
                <a href="{{ route('posts.index') }}"
                    class="block w-full text-left px-4 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-800">
                    Posts
                </a>
            @endcan

            @can('view categories')
                <a href="{{ route('categories.index') }}"
                    class="block w-full text-left px-4 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-800">
                    Categories
                </a>
            @endcan

            @can('view tags')
                <a href="{{ route('tags.index') }}"
                    class="block w-full text-left px-4 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-800">
                    Tags
                </a>
            @endcan

            @can('view dashboard')
                <a href="{{ route('admin.analytics') }}"
                    class="block w-full text-left px-4 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-800">
                    Analytics
                </a>
            @endcan

            @can('view comments')
                <a href="{{ route('comments.index') }}"
                    class="block w-full text-left px-4 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-800">
                    Comments
                </a>
            @endcan

            <div class="border-t border-gray-200 dark:border-slate-700 my-2"></div>

            <button type="button"
                class="block w-full text-left px-4 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-800"
                x-data @click="$store.theme.toggle()">
                <span class="theme-label-dark">Switch to Dark Mode</span>
                <span class="theme-label-light">Switch to Light Mode</span>
            </button>

            <a href="{{ route('home') }}" target="_blank"
                class="block w-full text-left px-4 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-800">
                View Site
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="block w-full text-left px-4 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-800">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>
