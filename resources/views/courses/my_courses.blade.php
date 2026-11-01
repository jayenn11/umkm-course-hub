<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-semibold text-xl text-[#243C4C] leading-tight">
                    {{ __('Kelas Saya') }}
                </h2>
                <p class="text-sm text-[#698696] mt-1">
                    Daftar kursus keterampilan UMKM yang sedang Anda ikuti.
                </p>
            </div>
            <a href="{{ route('courses.index') }}"
               class="inline-flex items-center px-4 py-2 bg-[#243C4C] hover:bg-[#1B2E3A] border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest transition">
                Jelajahi Kursus Lainnya
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-[#F0FDF4] border border-[#BBF7D0] text-[#166534] rounded-lg p-4 text-sm font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2 text-[#166534] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($enrollments->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($enrollments as $enrollment)
                        @php
                            $course = $enrollment->course;
                        @endphp
                        @if($course)
                            <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                                <div>
                                    <div class="relative h-40 bg-[#243C4C] flex items-center justify-center">
                                        @if($course->thumbnail)
                                            <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-12 h-12 rounded-xl bg-white/10 text-[#5289AD] flex items-center justify-center">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="absolute top-3 left-3">
                                            <span class="inline-flex items-center bg-white/85 text-[#243C4C] text-xs font-semibold px-2.5 py-1 rounded-md shadow-xs backdrop-blur-xs border border-white/30">
                                                {{ $course->category->name ?? 'Umum' }}
                                            </span>
                                        </div>
                                        <div class="absolute top-3 right-3">
                                            @if($enrollment->status === 'completed')
                                                <span class="inline-flex items-center bg-[#F0FDF4] text-[#166534] border border-[#BBF7D0] font-medium text-xs px-2.5 py-1 rounded-md">
                                                    Selesai
                                                </span>
                                            @else
                                                <span class="inline-flex items-center bg-[#EBF3F6] text-[#243C4C] border border-[#CBDDE6] font-medium text-xs px-2.5 py-1 rounded-md">
                                                    Aktif Belajar
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="p-6">
                                        <h3 class="text-lg font-bold text-[#243C4C] line-clamp-2">
                                            <a href="{{ route('courses.show', $course->slug) }}" class="hover:text-[#5289AD] transition">
                                                {{ $course->title }}
                                            </a>
                                        </h3>
                                        <p class="mt-2 text-xs text-[#698696]">
                                            Terdaftar pada: <span class="font-medium text-[#243C4C]">{{ $enrollment->enrolled_at ? $enrollment->enrolled_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}</span>
                                        </p>
                                        <p class="mt-1 text-xs text-[#698696]">
                                            Total Materi: <span class="font-medium text-[#243C4C]">{{ $course->lessons->count() }} Bab</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="px-6 pb-6 pt-3 border-t border-gray-100 flex items-center justify-between">
                                    <a href="{{ route('lessons.player', $course->slug) }}"
                                       class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-[#243C4C] hover:bg-[#1B2E3A] border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest transition">
                                        Masuk Ruang Belajar
                                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $enrollments->links() }}
                </div>
            @else
                <div class="bg-white rounded-xl shadow-xs border border-gray-200 p-12 text-center">
                    <svg class="w-12 h-12 text-[#698696] mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <h3 class="text-base font-semibold text-[#243C4C]">Anda Belum Mengikuti Kursus</h3>
                    <p class="mt-1 text-sm text-[#698696]">Mulai tingkatkan keterampilan usaha Anda dengan memilih kursus gratis di katalog kami.</p>
                    <div class="mt-5">
                        <a href="{{ route('courses.index') }}"
                           class="inline-flex items-center px-5 py-2.5 bg-[#243C4C] hover:bg-[#1B2E3A] text-white text-xs font-semibold uppercase tracking-widest rounded-lg transition">
                            Lihat Katalog Kursus
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
