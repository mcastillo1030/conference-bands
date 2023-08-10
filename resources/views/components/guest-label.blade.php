@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm dark:text-ash-400']) }}>
    {{ $value ?? $slot }}
</label>
