<span {{ $attributes->merge(['class' => 'hidden w-max max-w-full absolute top-0 left-0 bg-slate-200 py-2 px-3 rounded-md font-xsm z-[1]']) }}>
    <span data-arrow class="absolute w-2 h-2 bg-inherit rotate-45 rounded-bl-sm"></span>
    {{ $value ?? $slot }}
</span>
