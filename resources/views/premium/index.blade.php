@extends('layouts.public')
@section('title', 'Premium')

@section('content')
    <section class="max-w-5xl mx-auto">
        <div class="rounded-2xl bg-gradient-to-br from-amber-50 via-white to-indigo-50 border border-amber-100 p-8 sm:p-10 dark:from-slate-900 dark:via-slate-900 dark:to-slate-900 dark:border-slate-700">
            <div class="flex flex-col gap-6">
                <div class="flex items-center gap-3">
                    <x-premium-badge class="text-xs" />
                    <span class="text-sm text-gray-600 dark:text-gray-300">Unlock the full library</span>
                </div>
                <h1 class="text-3xl sm:text-5xl font-black text-gray-900 dark:text-gray-100">Premium access to members-only posts</h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl">
                    Go deeper with exclusive tutorials, early drafts, and deep dives that never hit the public feed.
                </p>
                <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ config('services.stripe.price_display') }}
                    <span class="text-sm font-normal text-gray-600 dark:text-gray-300">cancel anytime</span>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    @auth
                        @if(auth()->user()->hasPremiumAccess())
                            <span class="inline-flex items-center justify-center rounded-md bg-green-100 px-5 py-2.5 text-sm font-semibold text-green-700 dark:bg-green-900/30 dark:text-green-200">
                                You are Premium
                            </span>
                        @else
                            <a href="{{ route('premium.checkout') }}"
                               class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                                Upgrade to Premium
                            </a>
                        @endif
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
                </div>
            </div>
        </div>

        <div class="mt-10 grid gap-6 sm:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">What you get</h2>
                <ul class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-300">
                    <li>Members-only posts and case studies</li>
                    <li>Early access to new series</li>
                    <li>Downloadable checklists and templates</li>
                    <li>Ad-free reading experience</li>
                </ul>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">How it works</h2>
                <ol class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-300">
                    <li>Choose a plan and complete checkout.</li>
                    <li>Premium status is applied to your account.</li>
                    <li>Premium posts unlock instantly.</li>
                </ol>
            </div>
        </div>

        <div class="mt-10 rounded-xl border border-dashed border-amber-300 bg-amber-50/60 p-6 text-sm text-amber-900 dark:border-amber-700 dark:bg-amber-900/20 dark:text-amber-200">
            Payment integration is intentionally left for you to implement. The checkout page is scaffolded and ready for your gateway.
        </div>
    </section>
@endsection
