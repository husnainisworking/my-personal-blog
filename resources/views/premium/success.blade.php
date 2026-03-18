@extends('layouts.public')
@section('title', 'Premium Activated')

@section('content')
    <section class="max-w-3xl mx-auto">
        <div class="rounded-xl border border-green-200 bg-green-50/70 p-8 text-center shadow-sm dark:border-green-800/60 dark:bg-green-900/20">
            <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-200">
                <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 0 1 .006 1.414l-7.5 7.5a1 1 0 0 1-1.414 0l-3.5-3.5a1 1 0 1 1 1.414-1.414l2.793 2.793 6.793-6.793a1 1 0 0 1 1.408 0z" clip-rule="evenodd" />
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100">Premium unlocked</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Thanks for subscribing. Your premium access will activate as soon as Stripe confirms payment.</p>
            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">Go to Home</a>
                <a href="{{ route('premium.index') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-slate-600 dark:text-gray-200 dark:hover:bg-slate-800">Premium Benefits</a>
            </div>
        </div>
    </section>
@endsection
