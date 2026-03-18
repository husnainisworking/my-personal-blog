@extends('layouts.public')
@section('title', 'Premium Checkout')

@section('content')
    <section class="max-w-3xl mx-auto">
        <div class="rounded-xl border border-gray-200 bg-white p-8 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="flex items-center gap-3 mb-4">
                <x-premium-badge class="text-xs" />
                <span class="text-sm text-gray-600 dark:text-gray-300">Checkout</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">Finish upgrading to Premium</h1>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
                You will be redirected to Stripe to complete your subscription.
            </p>

            <div class="rounded-lg border border-gray-200 p-5 text-sm text-gray-600 dark:border-slate-700 dark:text-gray-300">
                <p class="font-semibold text-gray-800 dark:text-gray-100 mb-2">Plan</p>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-200">Premium Monthly</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Cancel anytime</p>
                    </div>
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ config('services.stripe.price_display') }}</div>
                </div>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <form action="{{ route('premium.checkout.session') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                        Continue to Stripe
                    </button>
                </form>
                <a href="{{ route('premium.index') }}"
                   class="inline-flex items-center justify-center rounded-md border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-slate-600 dark:text-gray-200 dark:hover:bg-slate-700">
                    Back to Premium
                </a>
            </div>
        </div>
    </section>
@endsection
