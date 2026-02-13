@extends('layouts.public')
@section('title', 'About')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-4xl font-bold text-gray-900 mb-6">About Me</h1>

    <div class="prose prose-lg text-gray-700">
        <p class="text-xl leading-relaxed mb-6">
            I'm Husnain Arshad, driven by Laravel, PHP, and evolving web paradigms.</p>

<p class="mb-6">
        This blog explores my perspectives on web development, coding best practices, and current tech trends.
</p>

<h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">What's On This Space</h2>
<ul class="space-y-4 mb-6">
            <li class="flex items-start gap-3">
                <svg class="w-6 h-6 text-indigo-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                </svg>
                <span>Practical web dev strategies and tools</span>
            </li>
            <li class="flex items-start gap-3">
                <svg class="w-6 h-6 text-indigo-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span>Detailed case studies of live builds.</span>
                </li>
            <li class="flex items-start gap-3">
                <svg class="w-6 h-6 text-indigo-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span>Actionable tips for sharper builds.</span>
            </li>
</ul>

<h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Get in Touch</h2>
<p class="mb-4">
        Have a question or want to connect? Feel free to reach out:
</p>
<ul class="list-none space-y-2">
    <li>Email: <a href="mailto:husnainisworking@gmail.com" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">husnainisworking@gmail.com</a></li>
</ul>
</div>
</div>
@endsection

            