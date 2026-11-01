<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-[#243C4C] leading-tight">
                    {{ __('Manajemen Kursus') }}
                </h2>
                <p class="text-sm text-[#698696] mt-1">
                    Kelola daftar kursus, status publikasi, serta modul materi pembelajaran.
                </p>
            </div>
            <button type="button"
                    x-data
                    @click="$dispatch('open-course-modal')"
                    onclick="window.dispatchEvent(new CustomEvent('open-course-modal'))"
                    class="inline-flex items-center px-4 py-2.5 bg-[#243C4C] hover:bg-[#1B2E3A] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition cursor-pointer">
                + Buat Kursus Baru
            </button>
        </div>
    </x-slot>

    <div class="py-10"
         x-data="{
            createModalOpen: @js(request('action') === 'create' || $errors->any()),
            editModalOpen: false,
            editCourse: {
                id: null,
                title: '',
                category_id: '',
                thumbnail: '',
                description: '',
                is_published: true,
                action: ''
            },
            deleteModalOpen: false,
            deleteTargetName: '',
            deleteActionUrl: '',
            openCreateModal() {
                this.createModalOpen = true;
            },
            openEditModal(course, actionUrl) {
                this.editCourse = {
                    id: course.id,
                    title: course.title,
                    category_id: String(course.category_id || ''),
                    thumbnail: course.thumbnail || '',
                    description: course.description || '',
                    is_published: Boolean(course.is_published),
                    action: actionUrl
                };
                this.editModalOpen = true;
            },
            openDeleteModal(name, actionUrl) {
                this.deleteTargetName = name;
                this.deleteActionUrl = actionUrl;
                this.deleteModalOpen = true;
            }
         }"
         @open-course-modal.window="openCreateModal()"
         @open-create-course-modal.window="openCreateModal()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
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

            <!-- Filter & Pencarian Interaktif (Live Search 300ms Debounce Tanpa Tombol Filter Redundan) -->
            <div class="bg-white p-5 rounded-xl shadow-xs border border-gray-200">
                <form id="admin-course-filter-form" method="GET" action="{{ route('admin.courses.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center" onsubmit="return false;">
                    <div class="sm:col-span-6 relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#698696]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text"
                               id="admin-course-search"
                               name="search"
                               value="{{ request('search') }}"
                               autocomplete="off"
                               placeholder="Ketik untuk mencari judul atau deskripsi kursus..."
                               class="w-full rounded-md border-gray-300 shadow-xs focus:border-[#5289AD] focus:ring-[#5289AD] text-sm pl-10 pr-9">
                        <div id="admin-search-spinner" class="hidden absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="animate-spin h-4 w-4 text-[#5289AD]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <select id="admin-course-category" name="category_id" class="w-full rounded-md border-gray-300 shadow-xs focus:border-[#5289AD] focus:ring-[#5289AD] text-sm">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-3">
                        <select id="admin-course-status" name="status" class="w-full rounded-md border-gray-300 shadow-xs focus:border-[#5289AD] focus:ring-[#5289AD] text-sm">
                            <option value="">Semua Status</option>
                            <option value="published" @selected(request('status') === 'published')>Published</option>
                            <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Tabel Daftar Kursus (Target Live Search Update) -->
            <div id="admin-courses-table-container" class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kursus</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Materi</th>
                                <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Peserta</th>
                                <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($courses as $course)
                                <tr class="hover:bg-gray-50/80">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-[#243C4C]">{{ $course->title }}</div>
                                        <div class="text-xs text-[#698696] mt-0.5 line-clamp-1">{{ Str::limit($course->description, 80) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $course->category->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($course->is_published)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-[#F0FDF4] text-[#166534] border border-[#BBF7D0]">
                                                Published
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                                Draft
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center font-medium text-[#243C4C]">
                                        {{ $course->lessons_count }} Bab
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center font-medium text-[#243C4C]">
                                        {{ $course->enrollments_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right space-x-1.5">
                                        <a href="{{ route('admin.courses.lessons.index', $course) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-[#EBF3F6] text-[#243C4C] hover:bg-[#dbe9ef] border border-[#CBDDE6] rounded-md text-xs font-semibold transition">
                                            Kelola Materi ({{ $course->lessons_count }})
                                        </a>
                                        <button type="button"
                                                @click="openEditModal({
                                                    id: {{ $course->id }},
                                                    title: @js($course->title),
                                                    category_id: @js($course->category_id),
                                                    thumbnail: @js($course->thumbnail),
                                                    description: @js($course->description),
                                                    is_published: @js((bool) $course->is_published)
                                                }, '{{ route('admin.courses.update', $course) }}')"
                                                class="inline-flex items-center px-3 py-1.5 bg-slate-100 text-[#243C4C] hover:bg-slate-200 border border-slate-200 rounded-md text-xs font-semibold transition cursor-pointer">
                                            Edit
                                        </button>
                                        <button type="button"
                                                @click="openDeleteModal(@js($course->title), '{{ route('admin.courses.destroy', $course) }}')"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 rounded-md text-xs font-semibold transition cursor-pointer">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-sm text-[#698696]">
                                        Belum ada data kursus yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($courses->hasPages())
                    <div class="px-6 py-4 bg-white border-t border-slate-200">
                        {{ $courses->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Modal Tambah Kursus Baru (Arctic Reflection Style) -->
        <div x-show="createModalOpen"
             x-cloak
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-create-course-title"
             role="dialog"
             aria-modal="true">
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                <div x-show="createModalOpen"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-[#243C4C]/60 backdrop-blur-xs transition-opacity"
                     @click="createModalOpen = false"></div>

                <div x-show="createModalOpen"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-[#ACBCBF]/50">
                    <div class="bg-[#243C4C] px-6 py-4 flex items-center justify-between">
                        <h3 class="text-base font-bold text-white" id="modal-create-course-title">
                            Tambah Kursus Baru
                        </h3>
                        <button type="button" @click="createModalOpen = false" class="text-slate-300 hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('admin.courses.store') }}" class="p-6 space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="create_title">
                                {{ __('Judul Kursus') }} <span class="text-rose-500 font-bold">*</span>
                            </x-input-label>
                            <x-text-input id="create_title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required placeholder="Contoh: Strategi Branding Produk Kuliner UMKM" />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="create_category_id">
                                    {{ __('Kategori Kursus') }} <span class="text-rose-500 font-bold">*</span>
                                </x-input-label>
                                <select id="create_category_id" name="category_id" required
                                        class="mt-1 block w-full border-gray-300 focus:border-[#5289AD] focus:ring-[#5289AD] rounded-md shadow-xs text-sm">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="create_thumbnail" :value="__('URL Gambar Thumbnail (Opsional)')" />
                                <x-text-input id="create_thumbnail" name="thumbnail" type="text" class="mt-1 block w-full" :value="old('thumbnail')" placeholder="https://contoh.com/banner.jpg" />
                                <x-input-error :messages="$errors->get('thumbnail')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="create_description">
                                {{ __('Deskripsi Lengkap Kursus') }} <span class="text-rose-500 font-bold">*</span>
                            </x-input-label>
                            <textarea id="create_description" name="description" rows="4" required
                                      class="mt-1 block w-full border-gray-300 focus:border-[#5289AD] focus:ring-[#5289AD] rounded-md shadow-xs text-sm"
                                      placeholder="Jelaskan manfaat, tujuan pembelajaran, dan target peserta UMKM dari kursus ini...">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center bg-[#F4FCFB] p-3 rounded-lg border border-[#ACBCBF]/40">
                            <input id="create_is_published" name="is_published" type="checkbox" value="1"
                                   @checked(old('is_published', true))
                                   class="rounded border-gray-300 text-[#243C4C] shadow-xs focus:ring-[#5289AD]">
                            <label for="create_is_published" class="ms-2 text-sm font-medium text-[#243C4C]">
                                Publikasikan kursus sekarang (tampil di katalog publik)
                            </label>
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                            <button type="button" @click="createModalOpen = false"
                                    class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                                Batal
                            </button>
                            <x-primary-button>
                                {{ __('Simpan Kursus') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Kursus (Arctic Reflection Style) -->
        <div x-show="editModalOpen"
             x-cloak
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-edit-course-title"
             role="dialog"
             aria-modal="true">
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                <div x-show="editModalOpen"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-[#243C4C]/60 backdrop-blur-xs transition-opacity"
                     @click="editModalOpen = false"></div>

                <div x-show="editModalOpen"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-[#ACBCBF]/50">
                    <div class="bg-[#243C4C] px-6 py-4 flex items-center justify-between">
                        <h3 class="text-base font-bold text-white" id="modal-edit-course-title">
                            Perbarui Data Kursus
                        </h3>
                        <button type="button" @click="editModalOpen = false" class="text-slate-300 hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form method="POST" :action="editCourse.action" class="p-6 space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="edit_course_title" class="block font-medium text-sm text-gray-700">
                                Judul Kursus <span class="text-rose-500 font-bold">*</span>
                            </label>
                            <input id="edit_course_title" name="title" type="text" x-model="editCourse.title" required
                                   class="mt-1 block w-full border-gray-300 focus:border-[#5289AD] focus:ring-[#5289AD] rounded-md shadow-xs text-sm">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="edit_course_category_id" class="block font-medium text-sm text-gray-700">
                                    Kategori Kursus <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <select id="edit_course_category_id" name="category_id" x-model="editCourse.category_id" required
                                        class="mt-1 block w-full border-gray-300 focus:border-[#5289AD] focus:ring-[#5289AD] rounded-md shadow-xs text-sm">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="edit_course_thumbnail" class="block font-medium text-sm text-gray-700">
                                    URL Gambar Thumbnail (Opsional)
                                </label>
                                <input id="edit_course_thumbnail" name="thumbnail" type="text" x-model="editCourse.thumbnail"
                                       placeholder="https://contoh.com/banner.jpg"
                                       class="mt-1 block w-full border-gray-300 focus:border-[#5289AD] focus:ring-[#5289AD] rounded-md shadow-xs text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="edit_course_description" class="block font-medium text-sm text-gray-700">
                                Deskripsi Lengkap Kursus <span class="text-rose-500 font-bold">*</span>
                            </label>
                            <textarea id="edit_course_description" name="description" rows="4" x-model="editCourse.description" required
                                      class="mt-1 block w-full border-gray-300 focus:border-[#5289AD] focus:ring-[#5289AD] rounded-md shadow-xs text-sm"></textarea>
                        </div>

                        <div class="flex items-center bg-[#F4FCFB] p-3 rounded-lg border border-[#ACBCBF]/40">
                            <input id="edit_course_is_published" name="is_published" type="checkbox" value="1"
                                   x-model="editCourse.is_published"
                                   class="rounded border-gray-300 text-[#243C4C] shadow-xs focus:ring-[#5289AD]">
                            <label for="edit_course_is_published" class="ms-2 text-sm font-medium text-[#243C4C]">
                                Publikasikan kursus (tampil di katalog publik)
                            </label>
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                            <button type="button" @click="editModalOpen = false"
                                    class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-[#243C4C] hover:bg-[#1B2E3A] text-white rounded-md font-semibold text-xs uppercase tracking-widest transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Custom Modal Konfirmasi Hapus Kursus (Arctic Reflection Style) -->
        <div x-show="deleteModalOpen"
             x-cloak
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-delete-course-title"
             role="dialog"
             aria-modal="true">
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                <div x-show="deleteModalOpen"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-[#243C4C]/60 backdrop-blur-xs transition-opacity"
                     @click="deleteModalOpen = false"></div>

                <div x-show="deleteModalOpen"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-[#ACBCBF]/50">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-rose-50 border border-rose-100 sm:mx-0">
                                <svg class="h-6 w-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-0.5 text-left">
                                <h3 class="text-base font-bold text-[#243C4C]" id="modal-delete-course-title">
                                    Konfirmasi Hapus Kursus
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-[#698696]">
                                        Apakah Anda yakin ingin menghapus kursus <span class="font-bold text-[#243C4C]" x-text="deleteTargetName"></span>? Pastikan seluruh materi pembelajaran di dalamnya sudah dihapus terlebih dahulu.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                            <button type="button"
                                    @click="deleteModalOpen = false"
                                    class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                                Batal
                            </button>
                            <form method="POST" :action="deleteActionUrl" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest shadow-xs transition">
                                    Hapus Data
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Search Script (Debounce 300ms via Fetch API) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('admin-course-search');
            const categorySelect = document.getElementById('admin-course-category');
            const statusSelect = document.getElementById('admin-course-status');
            const filterForm = document.getElementById('admin-course-filter-form');
            const tableContainer = document.getElementById('admin-courses-table-container');
            const spinner = document.getElementById('admin-search-spinner');
            let debounceTimer = null;
            let activeController = null;

            if (!searchInput || !tableContainer || !filterForm) return;

            function performAdminLiveSearch() {
                const url = new URL(filterForm.action, window.location.origin);
                const searchVal = searchInput.value.trim();
                const categoryVal = categorySelect ? categorySelect.value : '';
                const statusVal = statusSelect ? statusSelect.value : '';

                if (searchVal) url.searchParams.set('search', searchVal);
                if (categoryVal) url.searchParams.set('category_id', categoryVal);
                if (statusVal) url.searchParams.set('status', statusVal);

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
                    const newTable = doc.getElementById('admin-courses-table-container');

                    if (newTable) {
                        tableContainer.innerHTML = newTable.innerHTML;
                    }
                    window.history.replaceState({}, '', url.toString());
                })
                .catch(function (err) {
                    if (err.name !== 'AbortError') {
                        console.error('Admin live search error:', err);
                    }
                })
                .finally(function () {
                    if (spinner) spinner.classList.add('hidden');
                });
            }

            searchInput.addEventListener('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(performAdminLiveSearch, 300);
            });

            if (categorySelect) {
                categorySelect.addEventListener('change', performAdminLiveSearch);
            }
            if (statusSelect) {
                statusSelect.addEventListener('change', performAdminLiveSearch);
            }
        });
    </script>
</x-app-layout>
