@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'block bg-ash-300/[.2] dark:bg-ash-200/[.05] border-2 shadow-gravel-blur-sm focus:shadow-gravel-blur-base dark:focus:shadow-maize-blur-base border-gravel-200/[.25] ring-0 focus:ring-transparent focus:border-gravel-700 dark:border-gravel-200 dark:focus:border-maize-600 rounded-md text-gravel-700 dark:text-ash-400']) !!}>
