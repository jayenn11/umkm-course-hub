<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center space-x-2 text-xs text-[#698696] mb-1">
                    <a href="{{ route('admin.courses.index') }}" class="text-[#5289AD] hover:text-[#243C4C] font-medium transition">Kelola Kursus</a>
                    <span>/</span>
                    <span class="text-[#243C4C] font-medium truncate">{{ $course->title }}</span>
                </div>
                <h2 class="font-semibold text-xl text-[#243C4C] leading-tight">
                    Manajemen Materi: {{ $course->title }}
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.courses.index') }}"
                   class="inline-flex items-center px-3.5 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                    &larr; Kembali
                </a>
                <button type="button"
                        x-data
                        @click="$dispatch('open-lesson-modal')"
                        onclick="window.dispatchEvent(new CustomEvent('open-lesson-modal'))"
                        class="inline-flex items-center px-4 py-2 bg-[#243C4C] hover:bg-[#1B2E3A] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition cursor-pointer">
                    + Tambah Materi Baru
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-10"
         x-data="{
            previewUrl: null,
            previewTitle: '',
            createModalOpen: @js($errors->any()),
            editModalOpen: false,
            editLesson: {
                id: null,
                title: '',
                video_url: '',
                content: '',
                action: ''
            },
            deleteModalOpen: false,
            deleteTargetName: '',
            deleteActionUrl: '',
            openCreateModal() {
                this.createModalOpen = true;
            },
            openEditModal(lesson, actionUrl) {
                this.editLesson = {
                    id: lesson.id,
                    title: lesson.title,
                    video_url: lesson.video_url || '',
                    content: lesson.content || '',
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
         @open-lesson-modal.window="openCreateModal()"
         @open-create-lesson-modal.window="openCreateModal()">
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

            <!-- Toast Notifikasi Reorder Drag & Drop -->
            <div id="reorder-toast" class="hidden bg-[#EBF3F6] border border-[#CBDDE6] text-[#243C4C] rounded-lg p-3.5 text-xs font-semibold flex items-center justify-between shadow-xs transition">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#5289AD]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span id="reorder-toast-text">Urutan materi berhasil diperbarui.</span>
                </div>
            </div>

            <!-- Modal/Panel Preview Video Embed -->
            <div x-show="previewUrl" x-cloak style="display: none;" class="bg-[#243C4C] rounded-xl overflow-hidden shadow-lg border border-slate-700 p-4">
                <div class="flex items-center justify-between text-white mb-3 px-2">
                    <span class="text-sm font-semibold" x-text="'Pratinjau Video: ' + previewTitle"></span>
                    <button type="button" @click="previewUrl = null; previewTitle = ''" class="text-xs bg-white/10 hover:bg-white/20 px-3 py-1 rounded-md text-slate-100">
                        Tutup Pratinjau &times;
                    </button>
                </div>
                <div class="aspect-video w-full max-w-3xl mx-auto bg-black rounded-lg overflow-hidden">
                    <template x-if="previewUrl">
                        <iframe :src="previewUrl" class="w-full h-full border-0" allowfullscreen></iframe>
                    </template>
                </div>
            </div>

            <!-- Daftar Materi Terurut (Drag & Drop SortableJS) -->
            <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h3 class="text-base font-bold text-[#243C4C]">Daftar Urutan Silabus Materi</h3>
                        <p class="text-xs text-[#698696] mt-0.5">Kategori: <span class="font-semibold text-[#243C4C]">{{ $course->category->name ?? '-' }}</span> &bull; Total {{ $lessons->count() }} Materi</p>
                    </div>
                    @if($lessons->count() > 1)
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#F4FCFB] border border-[#ACBCBF]/50 text-xs text-[#698696]">
                            <svg class="w-4 h-4 text-[#5289AD]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                            </svg>
                            <span>Tarik &amp; lepas (Drag &amp; Drop) baris untuk mengubah urutan bab</span>
                        </div>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">Urutan</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul & Ringkasan Materi</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Video Embed URL</th>
                                <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-lessons-tbody" class="bg-white divide-y divide-gray-100">
                            @forelse($lessons as $lesson)
                                <tr data-id="{{ $lesson->id }}" class="hover:bg-gray-50/80 transition group">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="inline-flex items-center gap-2">
                                            <span class="drag-handle cursor-grab active:cursor-grabbing text-[#698696] hover:text-[#243C4C] p-1 rounded" title="Geser untuk mengubah urutan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                                                </svg>
                                            </span>
                                            <span class="lesson-order-badge inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#EBF3F6] text-[#243C4C] border border-[#CBDDE6] font-bold text-xs">
                                                {{ $lesson->order }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-[#243C4C]">{{ $lesson->title }}</div>
                                        @if($lesson->content)
                                            <div class="text-xs text-[#698696] mt-0.5 line-clamp-1">{{ Str::limit(strip_tags($lesson->content), 90) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($lesson->video_url)
                                            <div class="flex items-center space-x-2">
                                                <button type="button"
                                                        @click="previewUrl = @js($lesson->video_url); previewTitle = @js($lesson->title)"
                                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-[#EBF3F6] text-[#243C4C] hover:bg-[#dbe9ef] border border-[#CBDDE6] transition cursor-pointer">
                                                    <svg class="w-3.5 h-3.5 mr-1 text-[#5289AD]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Preview Video
                                                </button>
                                                <a href="{{ $lesson->video_url }}" target="_blank" rel="noopener noreferrer" class="text-xs text-[#698696] hover:text-[#243C4C] truncate max-w-[160px]" title="{{ $lesson->video_url }}">
                                                    {{ $lesson->video_url }}
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-xs text-[#698696] italic">Tanpa Video (Teks saja)</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right space-x-1.5">
                                        <button type="button"
                                                @click="openEditModal({
                                                    id: {{ $lesson->id }},
                                                    title: @js($lesson->title),
                                                    video_url: @js($lesson->video_url),
                                                    content: @js($lesson->content)
                                                }, '{{ route('admin.courses.lessons.update', [$course, $lesson]) }}')"
                                                class="inline-flex items-center px-3 py-1.5 bg-slate-100 text-[#243C4C] hover:bg-slate-200 border border-slate-200 rounded-md text-xs font-semibold transition cursor-pointer">
                                            Edit
                                        </button>
                                        <button type="button"
                                                @click="openDeleteModal(@js($lesson->title), '{{ route('admin.courses.lessons.destroy', [$course, $lesson]) }}')"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 rounded-md text-xs font-semibold transition cursor-pointer">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-sm text-[#698696]">
                                        Belum ada materi pada kursus ini. Silakan klik tombol <strong>+ Tambah Materi Baru</strong> di atas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Materi Baru (Arctic Reflection Style - Tanpa Input Manual Order) -->
        <div x-show="createModalOpen"
             x-cloak
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-create-lesson-title"
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
                        <div>
                            <h3 class="text-base font-bold text-white" id="modal-create-lesson-title">
                                Tambah Materi Pembelajaran Baru
                            </h3>
                            <p class="text-xs text-slate-300 mt-0.5">Urutan bab akan ditentukan secara otomatis di urutan terakhir.</p>
                        </div>
                        <button type="button" @click="createModalOpen = false" class="text-slate-300 hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('admin.courses.lessons.store', $course) }}" class="p-6 space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="create_lesson_title">
                                {{ __('Judul Materi / Bab') }} <span class="text-rose-500 font-bold">*</span>
                            </x-input-label>
                            <x-text-input id="create_lesson_title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required placeholder="Contoh: Pengenalan Laporan Arus Kas" />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="create_lesson_video_url" :value="__('URL Video Embed (YouTube / Vimeo - Opsional)')" />
                            <x-text-input id="create_lesson_video_url" name="video_url" type="url" class="mt-1 block w-full" :value="old('video_url')" placeholder="https://www.youtube.com/embed/dQw4w9WgXcQ" />
                            <p class="mt-1 text-xs text-[#698696]">Gunakan format URL embed (contoh: <code>https://www.youtube.com/embed/ID_VIDEO</code>).</p>
                            <x-input-error :messages="$errors->get('video_url')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="create_lesson_content" :value="__('Isi Materi / Catatan Bacaan (Opsional)')" />
                            <textarea id="create_lesson_content" name="content" rows="5"
                                      class="mt-1 block w-full border-gray-300 focus:border-[#5289AD] focus:ring-[#5289AD] rounded-md shadow-xs text-sm"
                                      placeholder="Tuliskan penjelasan lengkap, rangkuman, atau poin penting dari materi ini...">{{ old('content') }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                            <button type="button" @click="createModalOpen = false"
                                    class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                                Batal
                            </button>
                            <x-primary-button>
                                {{ __('Simpan Materi') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Materi (Arctic Reflection Style - Tanpa Input Manual Order) -->
        <div x-show="editModalOpen"
             x-cloak
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-edit-lesson-title"
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
                        <h3 class="text-base font-bold text-white" id="modal-edit-lesson-title">
                            Edit Materi Pembelajaran
                        </h3>
                        <button type="button" @click="editModalOpen = false" class="text-slate-300 hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form method="POST" :action="editLesson.action" class="p-6 space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="edit_lesson_title" class="block font-medium text-sm text-gray-700">
                                Judul Materi / Bab <span class="text-rose-500 font-bold">*</span>
                            </label>
                            <input id="edit_lesson_title" name="title" type="text" x-model="editLesson.title" required
                                   class="mt-1 block w-full border-gray-300 focus:border-[#5289AD] focus:ring-[#5289AD] rounded-md shadow-xs text-sm">
                        </div>

                        <div>
                            <label for="edit_lesson_video_url" class="block font-medium text-sm text-gray-700">
                                URL Video Embed (YouTube / Vimeo - Opsional)
                            </label>
                            <input id="edit_lesson_video_url" name="video_url" type="url" x-model="editLesson.video_url"
                                   placeholder="https://www.youtube.com/embed/dQw4w9WgXcQ"
                                   class="mt-1 block w-full border-gray-300 focus:border-[#5289AD] focus:ring-[#5289AD] rounded-md shadow-xs text-sm">
                            <p class="mt-1 text-xs text-[#698696]">Gunakan format URL embed (contoh: <code>https://www.youtube.com/embed/ID_VIDEO</code>).</p>
                        </div>

                        <div>
                            <label for="edit_lesson_content" class="block font-medium text-sm text-gray-700">
                                Isi Materi / Catatan Bacaan (Opsional)
                            </label>
                            <textarea id="edit_lesson_content" name="content" rows="5" x-model="editLesson.content"
                                      class="mt-1 block w-full border-gray-300 focus:border-[#5289AD] focus:ring-[#5289AD] rounded-md shadow-xs text-sm"></textarea>
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

        <!-- Custom Modal Konfirmasi Hapus Materi (Arctic Reflection Style) -->
        <div x-show="deleteModalOpen"
             x-cloak
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-delete-lesson-title"
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
                                <h3 class="text-base font-bold text-[#243C4C]" id="modal-delete-lesson-title">
                                    Konfirmasi Hapus Materi
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-[#698696]">
                                        Apakah Anda yakin ingin menghapus materi <span class="font-bold text-[#243C4C]" x-text="deleteTargetName"></span> dari kursus ini? Tindakan ini tidak dapat dibatalkan.
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

    <!-- SortableJS CDN & Drag-and-Drop Reorder Script -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tbody = document.getElementById('sortable-lessons-tbody');
            const toast = document.getElementById('reorder-toast');
            const toastText = document.getElementById('reorder-toast-text');
            let toastTimeout = null;

            if (!tbody || typeof Sortable === 'undefined') return;

            const rows = tbody.querySelectorAll('tr[data-id]');
            if (rows.length <= 1) return;

            Sortable.create(tbody, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'bg-[#F4FCFB]',
                onEnd: function () {
                    const currentRows = Array.from(tbody.querySelectorAll('tr[data-id]'));
                    const orderedIds = currentRows.map(function (row, idx) {
                        const badge = row.querySelector('.lesson-order-badge');
                        if (badge) {
                            badge.textContent = idx + 1;
                        }
                        return parseInt(row.getAttribute('data-id'), 10);
                    });

                    fetch('{{ route('admin.courses.lessons.reorder', $course) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ orders: orderedIds })
                    })
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        if (toast && toastText) {
                            toastText.textContent = data.message || 'Urutan materi berhasil diperbarui.';
                            toast.classList.remove('hidden');
                            clearTimeout(toastTimeout);
                            toastTimeout = setTimeout(function () {
                                toast.classList.add('hidden');
                            }, 3000);
                        }
                    })
                    .catch(function (err) {
                        console.error('Reorder error:', err);
                    });
                }
            });
        });
    </script>
</x-app-layout>
