@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#5289AD] focus:ring-[#5289AD] rounded-md shadow-sm']) }}>
