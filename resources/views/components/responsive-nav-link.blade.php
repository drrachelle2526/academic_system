@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-sky-500 text-start text-base font-semibold text-sky-800 bg-sky-50 focus:outline-none focus:text-sky-900 focus:bg-sky-100 focus:border-sky-700 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-slate-600 hover:text-sky-800 hover:bg-sky-50 hover:border-sky-200 focus:outline-none focus:text-sky-800 focus:bg-sky-50 focus:border-sky-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
