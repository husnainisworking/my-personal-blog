@props([
    'title' => 'Subscribe to Newsletter',
    'description' => 'Get the latest posts delivered to your inbox',
    'buttonText' => 'Subscribe'    
    ])

    <div {{ $attributes->merge(['class' => 'bg-indigo-50 dark:bg-gray-800 p-6 rounded-lg border border-indigo-200 dark:border-gray-700'])}}>
    <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">{{ $title }}</h3>
    <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $description }}</p>


    @if(session('newsletter_success'))
    <div class="mb-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded">
        {{ session('newsletter_success') }}
</div>
@endif

@if(session('newsletter_error'))
    <div class="mb-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 px-4 py-3 rounded">
        {{ session('newsletter_error') }}
</div>
@endif

<form action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-4">
    @csrf 
    @honeypot

    <div class="flex flex-col sm:flex-row gap-2">
        <input
            type="email"
            name="email"
            placeholder="Enter your email"
            required
            class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-md focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-500 @enderror"
            >
            <button
            type="submit"
            class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition-colors duration-200 whitespace-nowrap"
            >
            {{ $buttonText }}
</button>
</div>

@error('email')
<p class="text-red-500 text-sm">{{ $message }}</p>
@enderror
</form>
</div>