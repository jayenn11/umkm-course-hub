<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div class="flex items-center space-x-2 text-sm text-[#698696] overflow-hidden">
                <a href="{{ route('courses.my_courses') }}" class="text-[#5289AD] hover:text-[#243C4C] font-medium flex-shrink-0 transition">Kelas Saya</a>
                <span>/</span>
                <span class="text-[#243C4C] font-semibold truncate">{{ $course->title }}</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-[#EBF3F6] text-[#243C4C] border border-[#CBDDE6]">
                    Materi {{ $currentLesson->order }} dari {{ $totalLessons }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ showCompletionModal: {{ session('course_completed') ? 'true' : 'false' }} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-[#F0FDF4] border border-[#BBF7D0] text-[#166534] rounded-lg p-4 text-sm font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2 text-[#166534] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            @php
                $isCurrentLessonCompleted = in_array($currentLesson->id, $completedLessonIds);
            @endphp

            <!-- Tata Letak Responsif Dua Kolom -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                <!-- Kolom Kiri / Area Utama (2/3 Lebar di Desktop) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Pemutar Video Embed Responsive -->
                    <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
                        @if($currentLesson->video_url)
                            <div class="aspect-video w-full bg-slate-900">
                                <iframe
                                    src="{{ $currentLesson->video_url }}"
                                    title="{{ $currentLesson->title }}"
                                    class="w-full h-full border-0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        @else
                            <div class="aspect-video w-full bg-[#243C4C] flex flex-col items-center justify-center text-white p-6 text-center">
                                <div class="w-14 h-14 rounded-2xl bg-white/10 text-[#5289AD] flex items-center justify-center mb-3">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <p class="text-base font-semibold text-slate-100">Materi Bacaan</p>
                                <p class="text-xs text-[#ACBCBF] mt-1">Silakan pelajari rangkuman materi tertulis di bawah ini.</p>
                            </div>
                        @endif

                        <!-- Bar Judul Materi, Navigasi Sebelumnya/Selanjutnya, & Tombol Toggle Selesai -->
                        <div class="p-6 space-y-5">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                <div>
                                    <span class="text-xs font-semibold uppercase tracking-wider text-[#5289AD]">
                                        Bab {{ $currentLesson->order }}
                                    </span>
                                    <h1 class="text-xl sm:text-2xl font-bold text-[#243C4C] mt-1">
                                        {{ $currentLesson->title }}
                                    </h1>
                                </div>

                                <!-- Form Toggle Status Selesai -->
                                <form method="POST" action="{{ route('lessons.toggle_complete', $currentLesson) }}" class="flex-shrink-0">
                                    @csrf
                                    @if($isCurrentLessonCompleted)
                                        <button type="submit"
                                                class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#5289AD] focus:ring-offset-2 transition">
                                            <svg class="w-4 h-4 mr-1.5 text-[#166534]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Batalkan Selesai
                                        </button>
                                    @else
                                        <button type="submit"
                                                class="inline-flex items-center px-4 py-2.5 bg-[#243C4C] hover:bg-[#1B2E3A] border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-[#5289AD] focus:ring-offset-2 transition">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Tandai Selesai
                                        </button>
                                    @endif
                                </form>
                            </div>

                            <!-- Tombol Navigasi Materi Sebelumnya & Selanjutnya -->
                            <div class="pt-4 border-t border-gray-100 flex items-center justify-between gap-4">
                                @if($previousLesson)
                                    <a href="{{ route('lessons.player', ['course' => $course->slug, 'lessonOrder' => $previousLesson->order]) }}"
                                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-xs text-gray-700 hover:bg-gray-50 transition">
                                        <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                        Materi Sebelumnya
                                    </a>
                                @else
                                    <span class="inline-flex items-center px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg font-medium text-xs text-gray-400 cursor-not-allowed">
                                        <svg class="w-4 h-4 mr-1.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                        Materi Sebelumnya
                                    </span>
                                @endif

                                @if($nextLesson)
                                    <a href="{{ route('lessons.player', ['course' => $course->slug, 'lessonOrder' => $nextLesson->order]) }}"
                                       class="inline-flex items-center px-4 py-2 bg-[#243C4C] hover:bg-[#1B2E3A] border border-transparent rounded-lg font-semibold text-xs text-white transition">
                                        Materi Selanjutnya
                                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @else
                                    <span class="inline-flex items-center px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg font-medium text-xs text-gray-400 cursor-not-allowed">
                                        Materi Selanjutnya
                                        <svg class="w-4 h-4 ml-1.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Area Teks Bacaan / Rangkuman Materi di Bawah Video -->
                    <div class="bg-white rounded-xl shadow-xs border border-gray-200 p-6 sm:p-8">
                        <h2 class="text-base font-bold text-[#243C4C] mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-[#5289AD]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Rangkuman & Catatan Materi
                        </h2>
                        <div class="prose max-w-none text-slate-700 text-sm sm:text-base leading-relaxed whitespace-pre-line">
                            {{ $currentLesson->content ?? 'Tidak ada catatan teks tambahan untuk materi ini.' }}
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan / Sidebar Navigasi Materi (1/3 Lebar di Desktop) -->
                <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden lg:sticky lg:top-6">
                    <!-- Header Sidebar & Bar Persentase Progres -->
                    <div class="p-6 border-b border-gray-100 bg-[#F4FCFB]">
                        <h2 class="text-base font-bold text-[#243C4C] line-clamp-2">
                            {{ $course->title }}
                        </h2>

                        <div class="mt-4">
                            <div class="flex items-center justify-between text-xs font-semibold mb-1.5">
                                <span class="text-[#243C4C]">Progres Belajar: {{ $progressPercentage }}%</span>
                                <span class="text-[#5289AD]">{{ $completedCount }}/{{ $totalLessons }} Selesai</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-[#243C4C] h-2.5 rounded-full transition-all duration-300" style="width: {{ $progressPercentage }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Modul / Materi Kursus -->
                    <div class="divide-y divide-gray-100 max-h-[480px] overflow-y-auto">
                        @foreach($lessons as $lessonItem)
                            @php
                                $isActive = $lessonItem->id === $currentLesson->id;
                                $isCompleted = in_array($lessonItem->id, $completedLessonIds);
                            @endphp
                            <a href="{{ route('lessons.player', ['course' => $course->slug, 'lessonOrder' => $lessonItem->order]) }}"
                               class="p-4 flex items-start gap-3 transition {{ $isActive ? 'bg-[#EBF3F6]/70 border-l-4 border-[#243C4C]' : 'hover:bg-gray-50' }}">
                                <!-- Indikator Status / Nomor Materi -->
                                <div class="flex-shrink-0 mt-0.5">
                                    @if($isCompleted)
                                        <span class="w-6 h-6 rounded-full bg-[#F0FDF4] text-[#166534] border border-[#BBF7D0] flex items-center justify-center" title="Selesai">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </span>
                                    @else
                                        <span class="w-6 h-6 rounded-full text-xs font-bold flex items-center justify-center {{ $isActive ? 'bg-[#243C4C] text-white' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $lessonItem->order }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Judul Materi -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium leading-snug {{ $isActive ? 'text-[#243C4C] font-semibold' : ($isCompleted ? 'text-slate-700' : 'text-slate-800') }}">
                                        {{ $lessonItem->title }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs {{ $isActive ? 'text-[#5289AD] font-medium' : 'text-slate-400' }}">
                                            Bab {{ $lessonItem->order }}
                                        </span>
                                        @if($isActive)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-[#EBF3F6] text-[#243C4C] border border-[#CBDDE6]">
                                                Sedang Dipelajari
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Footer Sidebar: Tombol Kembali ke Kelas Saya -->
                    <div class="p-4 border-t border-gray-100 bg-slate-50">
                        <a href="{{ route('courses.my_courses') }}"
                           class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke Kelas Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Modal Penyelesaian Kursus -->
        <div x-show="showCompletionModal"
             x-cloak
             style="display: none;"
             class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto px-4 py-6 sm:px-0"
             aria-labelledby="modal-title"
             role="dialog"
             aria-modal="true">
            <!-- Backdrop -->
            <div x-show="showCompletionModal"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-[#243C4C]/60 backdrop-blur-xs transition-opacity"
                 @click="showCompletionModal = false"></div>

            <!-- Panel Dialog Modal -->
            <div x-show="showCompletionModal"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-2 sm:scale-95"
                 class="relative bg-white rounded-xl shadow-lg border border-gray-200 max-w-md w-full p-6 sm:p-8 text-center z-10 overflow-hidden">
                <!-- Ikon Status Selesai -->
                <div class="mx-auto w-12 h-12 rounded-full bg-[#F0FDF4] text-[#166534] border border-[#BBF7D0] flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <h3 id="modal-title" class="text-lg font-bold text-[#243C4C]">
                    Kursus Berhasil Diselesaikan
                </h3>

                <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                    Seluruh materi pembelajaran pada kursus ini telah selesai dipelajari. Anda dapat mengulas kembali materi kapan saja atau kembali ke halaman Kelas Saya.
                </p>

                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <button type="button"
                            @click="showCompletionModal = false"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                        Ulas Kembali Materi
                    </button>
                    <a href="{{ route('courses.my_courses') }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-[#243C4C] hover:bg-[#1B2E3A] border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest transition">
                        Ke Kelas Saya
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
