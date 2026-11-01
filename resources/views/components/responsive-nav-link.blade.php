@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[#243C4C] text-start text-base font-medium text-[#243C4C] bg-[#F4FCFB] focus:outline-none focus:text-[#243C4C] focus:bg-[#EBF3F6] focus:border-[#243C4C] transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-[#698696] hover:text-[#243C4C] hover:bg-gray-50 hover:border-[#ACBCBF] focus:outline-none focus:text-[#243C4C] focus:bg-gray-50 focus:border-[#ACBCBF] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
