<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center h-10 px-6 bg-indigo-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-slate-900 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
