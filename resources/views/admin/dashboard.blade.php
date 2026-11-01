<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-[#243C4C] leading-tight">
                    {{ __('Dashboard Administrator') }}
                </h2>
                <p class="text-sm text-[#698696] mt-1">
                    Ringkasan metrik operasional dan aktivitas pendaftaran peserta UMKM Course Hub.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.courses.index', ['action' => 'create']) }}"
                   class="inline-flex items-center px-4 py-2 bg-[#243C4C] hover:bg-[#1B2E3A] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition">
                    + Tambah Kursus
                </a>
                <a href="{{ route('admin.categories.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                    Kelola Kategori
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Kartu Metrik Ringkasan (Cards UI) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Kursus -->
                <div class="bg-white rounded-xl shadow-xs border border-gray-200 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-[#698696]">Total Kursus</p>
                        <p class="text-3xl font-bold text-[#243C4C] mt-2">{{ $totalCourses }}</p>
                        <a href="{{ route('admin.courses.index') }}" class="inline-block mt-2 text-xs font-medium text-[#5289AD] hover:text-[#243C4C] hover:underline transition">
                            Lihat semua kursus &rarr;
                        </a>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#EBF3F6] text-[#243C4C] border border-[#CBDDE6] flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>

                <!-- Total Materi -->
                <div class="bg-white rounded-xl shadow-xs border border-gray-200 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-[#698696]">Total Materi</p>
                        <p class="text-3xl font-bold text-[#243C4C] mt-2">{{ $totalLessons }}</p>
                        <span class="inline-block mt-2 text-xs text-[#698696]">
                            Video & modul bacaan
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-[#243C4C] border border-slate-200 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

                <!-- Total Peserta -->
                <div class="bg-white rounded-xl shadow-xs border border-gray-200 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-[#698696]">Total Peserta UMKM</p>
                        <p class="text-3xl font-bold text-[#243C4C] mt-2">{{ $totalPeserta }}</p>
                        <span class="inline-block mt-2 text-xs text-[#698696]">
                            Pelaku usaha terdaftar
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#F0FDF4] text-[#166534] border border-[#BBF7D0] flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Total Enrollment -->
                <div class="bg-white rounded-xl shadow-xs border border-gray-200 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-[#698696]">Total Pendaftaran</p>
                        <p class="text-3xl font-bold text-[#243C4C] mt-2">{{ $totalEnrollments }}</p>
                        <span class="inline-block mt-2 text-xs text-[#698696]">
                            Akumulasi kelas diikuti
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#EBF3F6] text-[#5289AD] border border-[#CBDDE6] flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tabel Riwayat Pendaftaran Terkini -->
            <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-[#243C4C]">Pendaftaran Kursus Terbaru</h3>
                        <p class="text-xs text-[#698696] mt-0.5">5 aktivitas pendaftaran kelas terakhir oleh peserta UMKM.</p>
                    </div>
                    <a href="{{ route('admin.courses.index') }}" class="text-xs font-semibold text-[#5289AD] hover:text-[#243C4C] transition">
                        Kelola Semua Kursus &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Peserta</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Usaha</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kursus</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($recentEnrollments as $enrollment)
                                <tr class="hover:bg-gray-50/80">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-semibold text-[#243C4C]">{{ $enrollment->user->name ?? '-' }}</div>
                                        <div class="text-xs text-[#698696]">{{ $enrollment->user->email ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                        {{ $enrollment->user->nama_usaha ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-[#243C4C] line-clamp-1">{{ $enrollment->course->title ?? '-' }}</div>
                                        <div class="text-xs text-[#5289AD]">{{ $enrollment->course->category->name ?? 'Umum' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($enrollment->status === 'completed')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-[#F0FDF4] text-[#166534] border border-[#BBF7D0]">
                                                Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-[#EBF3F6] text-[#243C4C] border border-[#CBDDE6]">
                                                Aktif Belajar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-[#698696]">
                                        {{ $enrollment->enrolled_at ? $enrollment->enrolled_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-[#698696]">
                                        Belum ada riwayat pendaftaran kursus oleh peserta.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
