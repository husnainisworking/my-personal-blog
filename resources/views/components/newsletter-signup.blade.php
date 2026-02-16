@props([
    'title' => 'Subscribe to Newsletter',
    'description' => 'Get the latest posts delivered to your inbox',
    'buttonText' => 'Subscribe'    
    ])

    <div {{ $attributes->merge(['class' => 'dark:bg-slate-900 p-4 rounded-lg'])}}>
    <h3 class="text-base text-center font-medium mb-2 text-gray-700 dark:text-gray-300">{{ $title }}</h3>
    <p class="text-gray-600 text-center dark:text-gray-300 mb-4">{{ $description }}</p>


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

<form action="{{ route('newsletter.subscribe') }}" 
        method="POST" 
        class="space-y-4"
        >
    @csrf 
    @honeypot

    <div class="relative rounded-md max-w-md mx-auto hover:shadow-[0_0_30px_rgba(129,140,248,0.6)] focus-within:shadow-[0_0_30px_rgba(129,140,248,0.6)] transition-shadow duration-300">
        <input
            type="email"
            name="email"
            placeholder="Enter your email"
            required
            class="w-full border border-gray-400 px-2 py-2 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-md pr-28 focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-500 @enderror"
            >
            
            <!-- Subscribe button -->
            <button
            type="submit"
            class="absolute right-1 bg-indigo-600 text-white px-4 top-1 bottom-1 flex items-center rounded text-sm hover:bg-indigo-700 transition-colors duration-200">
            {{ $buttonText }}
</button>
</div>
@error('email')
<p class="text-red-500 text-sm text-center">{{ $message }}</p>
@enderror
</form>
</div>