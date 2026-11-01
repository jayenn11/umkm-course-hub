@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[#243C4C] text-sm font-semibold leading-5 text-[#243C4C] focus:outline-none focus:border-[#243C4C] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-[#698696] hover:text-[#243C4C] hover:border-[#ACBCBF] focus:outline-none focus:text-[#243C4C] focus:border-[#ACBCBF] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
