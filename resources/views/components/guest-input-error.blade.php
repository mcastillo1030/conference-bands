@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'absolute z-[2] top-full before:absolute before:content-[""] before:top-0 before:left-1/2 before:border-l-4 before:border-l-transparent before:border-r-4 before:border-r-transparent before:border-b-8 before:border-b-ash-400 px-4 py-2 bg-ash-400 before:-translate-x-1/2 before:-translate-y-full rounded-md shadow text-sm text-tangerine-600']) }}>{{ $message }}</p>
@enderror
