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
            const prefersDark = 
                window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const useDark = stored ? stored === 'dark' : prefersDark;
            document.documentElement.classList.toggle('dark', useDark);
         }) ();
         </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col bg-gray-100">
            <!-- Navigation Bar -->
            <nav class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <!-- Blog Title (Left/Center) -->
                         <div class="flex-1 flex justify-center">
                            <span class="text-2xl font-bold text-indigo-600">
                                My Personal Blog
        </span>
        </div>

        <!-- Dark Mode Toggle (Right) -->
         <div class="flex items-center">
            <button
                type="button"
                class="flex min-w-[6rem] items-center justify-center gap-2 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 hover:bg-gray-50"
                x-data
                @click="$store.theme.toggle()">
                <!-- Moon Icon -->
                 <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M21.64 13a1 1 0 0 0-1.05-.14A8 8 0 0 1 11.14 3.4a1 1 0 0 0-1.19-1.19A10 10 0 1 0 22 14.05a1 1 0 0 0-.36-1.05Z"/>
                    </svg>
                <!-- Labels swap via CSS in resources/css/app.css -->
                 <span class="theme-label-dark">Dark</span>
                 <span class="theme-label-light">Light</span>
        </button>
        </div>
        </div>
        </div>
        </nav>


















            <!-- Main Content -->
             <div class="flex-1 flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
