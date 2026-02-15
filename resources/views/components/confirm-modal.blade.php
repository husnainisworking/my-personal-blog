@props([
    'title' => 'Are you sure?',
    'message' => 'This action cannot be undone.',
    'confirmText' => 'Delete',
    'cancelText' => 'Cancel'
])

<div x-data="{ open: false, formRef: null }">
    <!-- Trigger: clicking this opens the modal -->
     <span @click="formRef = $el.closest('[x-data]').parentElement.querySelector('form.confirm-form'); open = true" class="cursor-pointer">
        {{ $slot }}
    </span>

    <!-- Modal backdrop + dialog -->
     <template x-teleport="body">
        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
    <!-- Backdrop -->
     <div x-show="open"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-black/50"
        @click="open = false"></div>

        <!-- Dialog -->
        <div x-show="open"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-white dark:bg-slate-800 rounded-lg shadow-xl p-6 w-full max-w-sm mx-4">

            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h3>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $message }}</p>

            <div class="mt-5 flex justify-end gap-3">
                <button type="button" @click="open = false"
                    class="h-9 px-4 rounded-md border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-600">
                    {{ $cancelText }}
                </button>
                <button type="button"
                    @click="open = false; formRef.submit()"
                    class="h-9 px-4 rounded-md bg-red-600 text-sm font-medium text-white hover:bg-red-700">
                    {{ $confirmText }}
                </button>
            </div>
        </div>
    </div>
</template>
</div>