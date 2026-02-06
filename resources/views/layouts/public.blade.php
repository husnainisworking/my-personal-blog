<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--
    charset="UTF-8" -> Ensures the page supports all characters(Eng, Urdu, emojis, etc.).
    viewport -> Makes the page responsive on mobile devices (scales properly).
    -->
    <title>@yield('title', 'Welcome') - My Personal Blog </title>
    <meta name="description" content="@yield('description', 'My personal blog about web development, Laravel, PHP, and technology. Read articles, tutorials, and insights.')">
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Welcome') - My Personal Blog">
    <meta property="og:description" content="@yield('description', 'My personal blog about web development, Laravel, PHP, and technology')">
    @yield('og-image')

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'Welcome') - My Personal Blog">
    <meta property="twitter:description"content="@yield('description', 'My personal blog about web development, Laravel, PHP, and technology')">

    <link rel="alternate" type="application/rss+xml" title="My Personal Blog" href="{{ url('/feed.xml') }}">
    <script>
        (() => {
            // Prevents a flash of light mode before JS loads
            const stored = localStorage.getItem('theme'); // 'dark' | 'light' | null
            const useDark = stored === 'dark';
            document.documentElement.classList.toggle('dark', useDark);
        })();
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="font-sans bg-gray-50 dark:bg-slate-900 min-h-screen flex flex-col">
    <x-public-nav />

    <main class="pt-8 pb-6 sm:pt-10 sm:pb-10 flex-1">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>
    <footer class="bg-gray-50 dark:bg-slate-900 border-t border-gray-200 dark:border-slate-700 mt-auto">
        <!-- Newsletter Signup -->
        <div class="pt-6 border-gray-200 dark:border-slate-700">
            <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
                <x-newsletter-signup title="Stay Updated"
                    description="Subscribe to get new posts delivered to your inbox" buttonText="Subscribe" />
            </div>
        </div>
        <div class="max-w-7xl mx-auto py-4 px-5 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-500">
                <p>© {{ date('Y') }} Personal Blog. All rights reserved.</p>

                <div class="flex items-center gap-4">
                    <a href="{{ route('about') }}" class="hover:text-gray-700">About</a>
                    <a href="mailto:husnainisworking@gmail.com" class="hover:text-gray-700">Contact</a>
                    <a href="{{ url('/feed.xml') }}" class="inline-flex items-center gap-1 hover:text-gray-700"
                        target="_blank" rel="noopener">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4 fill-current">
                            <path
                                d="M6.18 17.82a2.18 2.18 0 1 1 0-4.36 2.18 2.18 0 0 1 0 4.36Zm-2.18-10v3.27a9.91 9.91 0 0 1 9.91 9.91h3.27C17.18 14.3 10.7 7.82 4 7.82Zm0-5v3.27c9.18 0 16.64 7.46 16.64 16.64H24C24 11.74 14.26 2 4 2Z" />
                        </svg>
                        RSS
                    </a>
                </div>
            </div>
        </div>
    </footer>
    <!-- This doesn't block rendering -->
 @livewireScriptConfig
 <script src="{{ asset('vendor/livewire/livewire.min.js') }}" defer></script>
 <script>
 /**
 * Alpine "store" = global state you can access anywhere in Blade via:
 *     $store.theme.isDark
 *     $store.theme.toggle()
 */
document.addEventListener('livewire:init', () => {
Alpine.store('theme', {
    // This is the global state (true = dark mode on)
    isDark: false,

    /** 
     * Runs once on page load.
     * - checks localStorage first (user preference)
     * - if no saved preference, uses OS setting
     * - adds/remove the "dark" class on <html>
    */
    init() {
        const stored = localStorage.getItem('theme'); // 'dark' | 'light' | null
        this.isDark = stored === 'dark';

        // Tailwind will apply dark styles when <html class="dark" exists
        document.documentElement.classList.toggle('dark', this.isDark);
    },
    /**
     * Set theme explicity + save it.
     * Call: $store.theme.set(true) or $store.theme.set(false)
     */
    set(isDark) {
        this.isDark = isDark;
        document.documentElement.classList.add('dark-transition');
        document.documentElement.classList.toggle('dark', isDark);
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        setTimeout(() => {
            document.documentElement.classList.remove('dark-transition');
        }, 200);
    },

    /**
     * Flip theme.
     * Call: $store.theme.toggle()
     */
    toggle() {
        this.set(!this.isDark);
    },
});
// IMPORTANT: Initialize the store after Alpine starts
Alpine.store('theme').init();
});
</script>
</body>
</html>
