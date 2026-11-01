<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-semibold text-xl text-[#243C4C] leading-tight">
                    {{ __('Katalog Kursus UMKM') }}
                </h2>
                <p class="text-sm text-[#698696] mt-1">
                    Tingkatkan keterampilan bisnis UMKM Anda melalui materi praktis dari para ahli.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Pencarian & Filter Kategori -->
            <div class="bg-white p-6 rounded-xl shadow-xs border border-gray-200">
                <form id="catalog-search-form" method="GET" action="{{ route('courses.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3" onsubmit="return false;">
                    @if(request('category'))
                        <input type="hidden" id="catalog-category-input" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="flex-1">
                        <label for="search" class="sr-only">Cari Kursus</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#698696]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input
                                type="text"
                                id="search"
                                name="search"
                                value="{{ request('search') }}"
                                autocomplete="off"
                                placeholder="Ketik untuk mencari judul atau topik kursus (misal: Keuangan, Pemasaran, Instagram)..."
                                class="w-full rounded-lg border-gray-300 shadow-xs focus:border-[#5289AD] focus:ring-[#5289AD] text-sm py-2.5 pl-10 pr-10"
                            >
                            <div id="catalog-search-spinner" class="hidden absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                <svg class="animate-spin h-4 w-4 text-[#5289AD]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <a id="catalog-reset-btn" href="{{ route('courses.index') }}" class="{{ (request('search') || request('category') || request('category_id')) ? 'inline-flex' : 'hidden' }} items-center justify-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition ease-in-out duration-150 shrink-0">
                        Reset
                    </a>
                </form>

                <!-- Bilah Filter Kategori Interaktif -->
                <div id="catalog-category-pills" class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap items-center gap-2">
                    <span class="text-xs font-medium text-[#698696] mr-1">Kategori:</span>
                    <a href="{{ route('courses.index', array_filter(['search' => request('search')])) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-medium transition {{ !request('category') && !request('category_id') ? 'bg-[#243C4C] hover:bg-[#1B2E3A] text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200' }}">
                        Semua Kategori
                    </a>
                    @foreach($categories as $category)
                        @php
                            $isActive = request('category') == $category->slug || request('category') == $category->id || request('category_id') == $category->id;
                        @endphp
                        <a href="{{ route('courses.index', array_filter(['category' => $category->slug, 'search' => request('search')])) }}"
                           class="px-3.5 py-1.5 rounded-full text-xs font-medium transition {{ $isActive ? 'bg-[#243C4C] hover:bg-[#1B2E3A] text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Grid Kartu Kursus (Live Search Target Container) -->
            <div id="catalog-results-container">
            @if($courses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($courses as $course)
                        <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden flex flex-col hover:shadow-md transition duration-200">
                            <!-- Thumbnail / Placeholder Solid Clean -->
                            <div class="relative h-48 bg-[#243C4C] flex items-center justify-center overflow-hidden">
                                @if($course->thumbnail)
                                    <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-center px-6">
                                        <div class="w-12 h-12 rounded-xl bg-white/10 text-[#5289AD] flex items-center justify-center mx-auto mb-2.5">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-slate-300 uppercase tracking-wider">UMKM Course Hub</span>
                                    </div>
                                @endif
                                <div class="absolute top-3 left-3">
                                    <span class="inline-flex items-center bg-white/85 text-[#243C4C] text-xs font-semibold px-2.5 py-1 rounded-md shadow-xs backdrop-blur-xs border border-white/30">
                                        {{ $course->category->name ?? 'Umum' }}
                                    </span>
                                </div>
                                @if(!empty($enrolledCourseIds) && in_array($course->id, $enrolledCourseIds))
                                    <div class="absolute top-3 right-3">
                                        <span class="inline-flex items-center bg-[#F0FDF4] text-[#166534] border border-[#BBF7D0] font-medium text-xs px-2.5 py-1 rounded-md shadow-xs">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Sudah Terdaftar
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Konten Kartu -->
                            <div class="p-6 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-[#243C4C] line-clamp-2 hover:text-[#5289AD] transition">
                                        <a href="{{ route('courses.show', $course->slug) }}">
                                            {{ $course->title }}
                                        </a>
                                    </h3>
                                    <p class="mt-2 text-sm text-slate-600 line-clamp-3">
                                        {{ Str::limit($course->description, 120) }}
                                    </p>
                                </div>

                                <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                                    <div class="flex items-center text-xs text-[#698696]">
                                        <svg class="w-4 h-4 mr-1.5 text-[#5289AD]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ $course->lessons->count() }} Materi Video</span>
                                    </div>

                                    <a href="{{ route('courses.show', $course->slug) }}"
                                       class="inline-flex items-center text-sm font-semibold text-[#5289AD] hover:text-[#243C4C] transition">
                                        Lihat Detail
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $courses->links() }}
                </div>
            @else
                <div class="bg-white rounded-xl shadow-xs border border-gray-200 p-12 text-center">
                    <svg class="w-12 h-12 text-[#698696] mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-base font-semibold text-[#243C4C]">Kursus Tidak Ditemukan</h3>
                    <p class="mt-1 text-sm text-[#698696]">Belum ada kursus yang sesuai dengan pencarian atau filter kategori yang Anda pilih.</p>
                    <div class="mt-4">
                        <a href="{{ route('courses.index') }}" class="inline-flex items-center px-4 py-2 bg-[#243C4C] hover:bg-[#1B2E3A] text-white text-xs font-semibold uppercase tracking-widest rounded-lg transition">
                            Tampilkan Semua Kursus
                        </a>
                    </div>
                </div>
            @endif
            </div>
        </div>
    </div>

    <!-- Live Search Script (Debounce 300ms via Fetch API) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search');
            const searchForm = document.getElementById('catalog-search-form');
            const resultsContainer = document.getElementById('catalog-results-container');
            const categoryPills = document.getElementById('catalog-category-pills');
            const resetBtn = document.getElementById('catalog-reset-btn');
            const spinner = document.getElementById('catalog-search-spinner');
            let debounceTimer = null;
            let activeController = null;

            if (!searchInput || !resultsContainer) return;

            function performLiveSearch() {
                const url = new URL(searchForm.action, window.location.origin);
                const searchVal = searchInput.value.trim();
                const categoryInput = document.getElementById('catalog-category-input');
                const categoryVal = categoryInput ? categoryInput.value.trim() : '';

                if (searchVal) url.searchParams.set('search', searchVal);
                if (categoryVal) url.searchParams.set('category', categoryVal);

                if (activeController) {
                    activeController.abort();
                }
                activeController = new AbortController();

                if (spinner) spinner.classList.remove('hidden');

                fetch(url.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    signal: activeController.signal
                })
                .then(function (response) {
                    return response.text();
                })
                .then(function (html) {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newResults = doc.getElementById('catalog-results-container');
                    const newPills = doc.getElementById('catalog-category-pills');

                    if (newResults) {
                        resultsContainer.innerHTML = newResults.innerHTML;
                    }
                    if (newPills && categoryPills) {
                        categoryPills.innerHTML = newPills.innerHTML;
                    }
                    if (resetBtn) {
                        if (searchVal || categoryVal) {
                            resetBtn.classList.remove('hidden');
                            resetBtn.classList.add('inline-flex');
                        } else {
                            resetBtn.classList.add('hidden');
                            resetBtn.classList.remove('inline-flex');
                        }
                    }
                    window.history.replaceState({}, '', url.toString());
                })
                .catch(function (err) {
                    if (err.name !== 'AbortError') {
                        console.error('Live search error:', err);
                    }
                })
                .finally(function () {
                    if (spinner) spinner.classList.add('hidden');
                });
            }

            searchInput.addEventListener('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(performLiveSearch, 300);
            });
        });
    </script>
</x-app-layout>
