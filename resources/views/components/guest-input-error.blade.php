@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'absolute z-[2] top-full before:absolute before:content-[""] before:top-0 before:left-1/2 before:border-l-4 before:border-l-transparent before:border-r-4 before:border-r-transparent before:border-b-8 before:border-b-tangerine-600 px-4 py-2 bg-tangerine-600 before:-translate-x-1/2 before:-translate-y-full rounded-md shadow text-sm text-ash-200']) }}>{{ $message }}</p>
@enderror
