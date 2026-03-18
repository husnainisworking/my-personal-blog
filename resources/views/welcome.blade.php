@extends('layouts.public')
@section('title', 'Home')

@section('content')
    <!-- Homepage (Welcome Page) of blog. -->
    <div class="mb-12 text-center">
        <h1 class="text-3xl sm:text-5xl font-bold text-gray-900 dark:text-gray-100 mb-4">Welcome to My Personal Blog</h1>
        <p class="text-xl text-gray-600">Sharing thoughts, ideas, and stories</p>
    </div>

    <div class="mb-10 rounded-xl border border-amber-200 bg-amber-50/70 p-6 text-center dark:border-amber-800/70 dark:bg-amber-900/20">
        <div class="flex items-center justify-center gap-2 mb-2">
            <x-premium-badge class="text-xs" />
            <span class="text-sm text-amber-900 dark:text-amber-200">Members-only posts available</span>
        </div>
        <p class="text-sm text-gray-700 dark:text-gray-200 mb-4">Get access to premium tutorials, deep dives, and downloadable resources.</p>
        <a href="{{ route('premium.index') }}" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
            See Premium
        </a>
    </div>

    <livewire:home-posts />

    <!-- Turnstile blur overlay (home page only) -->
<div id="turnstile-overlay" style="position:fixed;inset:0;backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);background:rgba(0,0,0,0.15);z-index:9998;transition:opacity 0.4s ease;"></div>
<div class="cf-turnstile" data-sitekey="0x4AAAAAACm7o185TSNmO-n_" data-theme="auto" data-callback="onTurnstileSuccess" style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:9999;"></div>
<script>
    var turnstileReady = false;
    var minTimeReached = false;
    function onTurnstileSuccess(token) {
        turnstileReady = true;
        if (minTimeReached) dismissOverlay();
    }
    function dismissOverlay() {
        var overlay = document.getElementById('turnstile-overlay');
        overlay.style.opacity = '0';
        setTimeout(function() {
            overlay.style.display = 'none';
            var widget = document.querySelector('.cf-turnstile');
            if (widget) widget.style.display = 'none';
        }, 400);
    }
    setTimeout(function() {
        minTimeReached = true;
        if (turnstileReady) dismissOverlay();
    }, 1500);
</script>

@endsection
