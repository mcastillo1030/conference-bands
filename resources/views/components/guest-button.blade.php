<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2  bg-transparent border border-ash-600 dark:border-ash-400 rounded-md font-semibold text-xs dark:text-ash-200 uppercase tracking-widest shadow-sm hover:bg-ash-200/[.75] dark:hover:bg-ash-200/10 focus:outline-none focus:ring-2 focus:ring-ash-300 dark:focus:ring-maize-600 focus:ring-offset-2 dark:focus:ring-offset-gravel-950 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
