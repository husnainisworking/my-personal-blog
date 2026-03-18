@extends('layouts.public')
@section('title', $post->title)
@section('description', $post->excerpt ?? Str::limit(strip_tags($post->content), 155))

@section('content')
    <article class="max-w-4xl mx-auto">
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => $post->category?->name ?? 'Uncategorized' , 'url' => $post->category ? route('categories.show', $post->category->slug) : route('home')],
            ['label' => $post->title],
        ]" />

        <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50/70 p-6 sm:p-8 dark:border-amber-800/70 dark:bg-amber-900/20">
            <div class="flex items-center gap-3 mb-4">
                <x-premium-badge class="text-xs" />
                <span class="text-sm text-amber-900 dark:text-amber-200">Members-only post</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-gray-100 mb-3">{{ $post->title }}</h1>
            <p class="text-sm text-gray-700 dark:text-gray-300 mb-6">
                {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 180) }}
            </p>

            <div class="rounded-xl bg-white/90 p-5 text-sm text-gray-700 shadow-sm dark:bg-slate-800 dark:text-gray-200">
                <p class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Unlock this premium post</p>
                <ul class="space-y-1">
                    <li>Read the full article and premium series.</li>
                    <li>Access downloadable resources and templates.</li>
                    <li>Enjoy an ad-free reading experience.</li>
                </ul>
                <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">Only {{ config('services.stripe.price_display') }}.</p>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                @auth
                    <a href="{{ route('premium.checkout') }}"
                       class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                        Upgrade to Premium
                    </a>
                @endauth
                @guest
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                        Create Account
                    </a>
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center rounded-md border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-slate-600 dark:text-gray-200 dark:hover:bg-slate-800">
                        Log In
                    </a>
                @endguest
                <a href="{{ route('premium.index') }}"
                   class="inline-flex items-center justify-center rounded-md border border-amber-300 px-5 py-2.5 text-sm font-semibold text-amber-900 hover:bg-amber-100 dark:border-amber-700 dark:text-amber-200 dark:hover:bg-amber-900/40">
                    See Premium Benefits
                </a>
            </div>
        </div>
    </article>
@endsection
