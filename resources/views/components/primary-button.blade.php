<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#243C4C] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#1B2E3A] focus:bg-[#1B2E3A] active:bg-[#15242E] focus:outline-none focus:ring-2 focus:ring-[#5289AD] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
