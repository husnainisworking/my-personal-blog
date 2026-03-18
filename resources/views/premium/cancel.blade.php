@extends('layouts.public')
@section('title', 'Checkout Canceled')

@section('content')
    <section class="max-w-3xl mx-auto">
        <div class="rounded-xl border border-amber-200 bg-amber-50/70 p-8 text-center shadow-sm dark:border-amber-800/60 dark:bg-amber-900/20">
            <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-200">
                <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.518 11.59c.75 1.334-.213 2.986-1.742 2.986H3.48c-1.53 0-2.492-1.652-1.743-2.986l6.52-11.59z" />
                    <path d="M11 13a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-1-7a1 1 0 0 1 1 1v4a1 1 0 1 1-2 0V7a1 1 0 0 1 1-1z" fill="white" />
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100">Checkout canceled</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">No worries. You can try again whenever you’re ready.</p>
            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('premium.checkout') }}" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">Try Again</a>
                <a href="{{ route('premium.index') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-slate-600 dark:text-gray-200 dark:hover:bg-slate-800">Back to Premium</a>
            </div>
        </div>
    </section>
@endsection
