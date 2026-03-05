@extends('layouts.public')
@section('title', 'Home')

@section('content')
    <!-- Homepage (Welcome Page) of blog. -->
    <div class="mb-12 text-center">
        <h1 class="text-3xl sm:text-5xl font-bold text-gray-900 dark:text-gray-100 mb-4">Welcome to My Personal Blog</h1>
        <p class="text-xl text-gray-600">Sharing thoughts, ideas, and stories</p>
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
