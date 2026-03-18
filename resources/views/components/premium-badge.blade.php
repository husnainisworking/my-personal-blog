@props(['class' => ''])

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full bg-amber-100 text-amber-900 text-[11px] font-semibold px-2 py-0.5 ring-1 ring-amber-200/70 dark:bg-amber-900/40 dark:text-amber-200 dark:ring-amber-700/70 ' . $class]) }}>
    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 0 0 .95.69h4.157c.969 0 1.372 1.24.588 1.81l-3.364 2.444a1 1 0 0 0-.364 1.118l1.286 3.955c.3.921-.755 1.688-1.539 1.118l-3.364-2.444a1 1 0 0 0-1.176 0l-3.364 2.444c-.784.57-1.838-.197-1.539-1.118l1.286-3.955a1 1 0 0 0-.364-1.118L2.07 9.382c-.784-.57-.38-1.81.588-1.81h4.157a1 1 0 0 0 .95-.69l1.286-3.955z" />
    </svg>
    <span>Premium</span>
</span>
