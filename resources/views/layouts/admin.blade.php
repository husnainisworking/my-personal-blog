<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <title>@yield('title', 'Admin') - My Personal Blog </title>
    <script>
        (() => {
            const stored = localStorage.getItem('theme');
            const useDark = stored === 'dark';
            document.documentElement.classList.toggle('dark', useDark);
        })();
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <!--
    This is admin layout template. All admin pages extend this so they
    share the same header,nav and styling.
    @yld('title', 'Admin') -> Placeholder for a page title. If a child view
    defines @sectn('title'), it will override; otherwise defaults to "Admin".
-->
</head>

<body class="font-sans bg-gray-100 dark:bg-slate-900">

    <x-admin-nav />

    <!--
    This is a flash message block, when you redirect back after an action (like submitting a comment or approving one), laravel stores a message in the session (with('success', 'Comment submitted!')).
    On the next page load, this block checks for that message and displays it in a styled alert box.
    After it's shown once the session clears it.
    -->
    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            <!--
            if($errors->any()) Blade conditional: checks if there are any validation errors in the current request.
            Example: if you submit a form without a required field, Laravel's validator will populate $errors.
            @yld('content') is placeholder for child views. whatever is being put in @sectn('content') in a child Blade file will be injected here.
            @forech($errors->all() as $error) , loops through all error messages, example: "title is required", "content must be at least 10 characters".
            -->
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
@livewireScripts
</body>


</html>
