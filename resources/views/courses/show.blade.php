<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2 text-sm text-[#698696]">
                <a href="{{ route('courses.index') }}" class="text-[#5289AD] hover:text-[#243C4C] font-medium transition">Katalog Kursus</a>
                <span>/</span>
                <span class="text-[#243C4C] font-semibold truncate">{{ $course->title }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if(session('success'))
                <div class="bg-[#F0FDF4] border border-[#BBF7D0] text-[#166534] rounded-lg p-4 text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Banner / Header Informasi Kursus -->
            <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3">
                    <!-- Kolom Kiri: Deskripsi & Informasi Utama -->
                    <div class="p-6 sm:p-8 lg:col-span-2 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-[#EBF3F6] text-[#243C4C] border border-[#CBDDE6]">
                                    {{ $course->category->name ?? 'Umum' }}
                                </span>
                                <span class="text-xs text-[#698696] flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-[#5289AD]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    {{ $course->lessons->count() }} Materi Pembelajaran
                                </span>
                            </div>

                            <h1 class="text-2xl sm:text-3xl font-bold text-[#243C4C] leading-tight">
                                {{ $course->title }}
                            </h1>

                            <div class="mt-4 text-slate-600 text-sm sm:text-base leading-relaxed whitespace-pre-line">
                                {{ $course->description }}
                            </div>
                        </div>

                        <!-- Tombol Aksi Pendaftaran (Enrollment) -->
                        <div class="mt-8 pt-6 border-t border-gray-100 flex flex-wrap items-center gap-4">
                            @guest
                                <a href="{{ route('login') }}"
                                   class="inline-flex items-center px-6 py-3 bg-[#243C4C] hover:bg-[#1B2E3A] border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-[#5289AD] focus:ring-offset-2 transition">
                                    Login untuk Mendaftar
                                </a>
                                <span class="text-xs text-[#698696]">Belum punya akun? <a href="{{ route('register') }}" class="text-[#5289AD] hover:text-[#243C4C] font-medium hover:underline">Daftar Peserta UMKM</a></span>
                            @else
                                @if(Auth::user()->role === 'peserta')
                                    @if($isEnrolled)
                                        <span class="inline-flex items-center px-3.5 py-2 rounded-md text-xs font-medium bg-[#F0FDF4] text-[#166534] border border-[#BBF7D0]">
                                            <svg class="w-4 h-4 mr-1.5 text-[#166534]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Terdaftar
                                        </span>
                                        <a href="{{ route('lessons.player', $course->slug) }}"
                                           class="inline-flex items-center px-6 py-3 bg-[#243C4C] hover:bg-[#1B2E3A] border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-[#5289AD] focus:ring-offset-2 transition">
                                            Lanjut Belajar
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                        </a>
                                    @else
                                        <form method="POST" action="{{ route('courses.enroll', $course) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center px-6 py-3 bg-[#243C4C] hover:bg-[#1B2E3A] border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-[#5289AD] focus:ring-offset-2 transition">
                                                Daftar Kursus Gratis
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                        Mode Pratinjau Administrator
                                    </span>
                                @endif
                            @endguest
                        </div>
                    </div>

                    <!-- Kolom Kanan: Visual Thumbnail Solid Clean -->
                    <div class="bg-[#243C4C] min-h-[240px] flex items-center justify-center p-8">
                        @if($course->thumbnail)
                            <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-full h-full object-cover rounded-lg shadow-sm">
                        @else
                            <div class="text-center text-white">
                                <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-white/10 text-[#5289AD] flex items-center justify-center">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold tracking-wide uppercase text-slate-100">Kelas Keterampilan UMKM</p>
                                <p class="text-xs text-[#ACBCBF] mt-1">Akses Belajar Mandiri</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Daftar Silabus Materi (Expandable jika > 5 Bab) -->
            @php
                $totalLessonsCount = $course->lessons->count();
                $extraLessonsCount = max(0, $totalLessonsCount - 5);
            @endphp
            <div class="bg-white rounded-xl shadow-xs border border-gray-200 p-6 sm:p-8" x-data="{ expanded: false }">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-[#243C4C]">Silabus Materi Pembelajaran</h2>
                        <p class="text-sm text-[#698696] mt-0.5">Daftar bab dan video yang akan Anda pelajari di kelas ini.</p>
                    </div>
                    <span class="text-xs font-semibold text-[#243C4C] bg-[#EBF3F6] border border-[#CBDDE6] px-3 py-1.5 rounded-md">
                        Total {{ $totalLessonsCount }} Bab
                    </span>
                </div>

                @if($totalLessonsCount > 0)
                    <div class="divide-y divide-gray-100 border border-gray-200 rounded-lg overflow-hidden">
                        @foreach($course->lessons as $index => $lesson)
                            <div @if($index >= 5)
                                     x-show="expanded"
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 -translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     style="display: none;"
                                 @endif
                                 class="p-4 sm:px-6 flex items-center justify-between hover:bg-[#F4FCFB] transition">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-[#EBF3F6] text-[#243C4C] border border-[#CBDDE6] flex items-center justify-center font-bold text-xs">
                                        {{ $lesson->order ?? ($index + 1) }}
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-semibold text-[#243C4C]">
                                            {{ $lesson->title }}
                                        </h3>
                                        @if($lesson->content)
                                            <p class="text-xs text-[#698696] mt-0.5 line-clamp-1">
                                                {{ Str::limit(strip_tags($lesson->content), 100) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center text-xs text-[#698696]">
                                    @if($lesson->video_url)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 border border-slate-200 font-medium">
                                            <svg class="w-3.5 h-3.5 mr-1 text-[#5289AD]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Video
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 border border-slate-200 font-medium">
                                            Bacaan
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($extraLessonsCount > 0)
                        <div class="mt-4 flex justify-center">
                            <button type="button"
                                    @click="expanded = !expanded"
                                    class="inline-flex items-center px-5 py-2.5 bg-white hover:bg-[#F4FCFB] text-[#243C4C] hover:text-[#5289AD] border border-[#ACBCBF] rounded-lg text-xs font-semibold tracking-wide transition shadow-2xs">
                                <span x-show="!expanded">Tampilkan Lebih Banyak ({{ $extraLessonsCount }} Bab Lainnya)</span>
                                <span x-show="expanded" style="display: none;">Ciutkan Materi</span>
                                <svg class="w-4 h-4 ml-1.5 text-[#5289AD] transition-transform duration-200" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>
                    @endif
                @else
                    <p class="text-sm text-[#698696] italic">Belum ada materi pembelajaran yang ditambahkan pada kursus ini.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
