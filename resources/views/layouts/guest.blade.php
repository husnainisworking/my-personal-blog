<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">


    <title>{{ config('app.name', 'My Personal Blog') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script>
        (() => {
            const stored = localStorage.getItem('theme');
            const useDark = stored === 'dark';
            document.documentElement.classList.toggle('dark', useDark);
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col bg-gray-100 dark:bg-slate-900">

        <x-guest-nav />


        <!-- Main Content -->
        <div class="flex-1 flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div
                class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-slate-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </div>
    @livewireScripts
<script>
    Alpine.store('theme', {
        isDark: false,
        init() {
            const stored = localStorage.getItem('theme');
            this.isDark = stored === 'dark';
            document.documentElement.classList.toggle('dark', this.isDark);
        },
        set(isDark) {
            this.isDark = isDark;
            document.documentElement.classList.add('dark-transition');
            document.documentElement.classList.toggle('dark', isDark);
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            setTimeout(() => {
                document.documentElement.classList.remove('dark-transition');
            }, 200);
        },
        toggle() {
            this.set(!this.isDark);
        },
    });
    Alpine.store('theme').init();
</script>
</body>

</html>
