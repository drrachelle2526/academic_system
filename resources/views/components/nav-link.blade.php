@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-sky-500 text-sm font-semibold leading-5 text-sky-950 focus:outline-none focus:border-sky-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-slate-500 hover:text-sky-800 hover:border-sky-200 focus:outline-none focus:text-sky-800 focus:border-sky-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
