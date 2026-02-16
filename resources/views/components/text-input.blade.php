@props(['disabled' => false])

<div class="relative underline-input border-b-2 border-gray-300 dark:border-gray-600">
<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-0  
px-3 rounded-none outline-none bg-transparent shadow-none
 focus:ring-0
  dark:bg-transparent dark:text-white
transition-colors duration-300
']) }}>
</div>
