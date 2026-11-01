<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-semibold text-xl text-[#243C4C] leading-tight">
                    {{ __('Manajemen Kategori Kursus') }}
                </h2>
                <p class="text-sm text-[#698696] mt-1">
                    Kelola pengelompokan topik pelatihan keterampilan UMKM.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-10"
         x-data="{
            editModalOpen: false,
            editCategory: { id: null, name: '', description: '', action: '' },
            deleteModalOpen: false,
            deleteTargetName: '',
            deleteActionUrl: '',
            openEditModal(category, actionUrl) {
                this.editCategory = {
                    id: category.id,
                    name: category.name,
                    description: category.description || '',
                    action: actionUrl
                };
                this.editModalOpen = true;
            },
            openDeleteModal(name, actionUrl) {
                this.deleteTargetName = name;
                this.deleteActionUrl = actionUrl;
                this.deleteModalOpen = true;
            }
         }">
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                <!-- Kolom Kiri: Form Tambah Kategori Baru -->
                <div class="bg-white rounded-xl shadow-xs border border-gray-200 p-6">
                    <h3 class="text-base font-bold text-[#243C4C] mb-4">Tambah Kategori Baru</h3>
                    <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="name">
                                {{ __('Nama Kategori') }} <span class="text-rose-500 font-bold">*</span>
                            </x-input-label>
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required placeholder="Contoh: Pemasaran Digital" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Deskripsi (Opsional)')" />
                            <textarea id="description" name="description" rows="3"
                                      class="mt-1 block w-full border-gray-300 focus:border-[#5289AD] focus:ring-[#5289AD] rounded-md shadow-xs text-sm"
                                      placeholder="Penjelasan singkat mengenai cakupan kategori ini...">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="pt-2">
                            <x-primary-button class="w-full justify-center">
                                {{ __('Simpan Kategori') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- Kolom Kanan: Tabel Daftar Kategori -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-base font-bold text-[#243C4C]">Daftar Kategori</h3>
                        <span class="text-xs font-medium text-[#698696]">Total: {{ $categories->total() }} Kategori</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Slug</th>
                                    <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah Kursus</th>
                                    <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($categories as $category)
                                    <tr class="hover:bg-gray-50/80">
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-[#243C4C]">{{ $category->name }}</div>
                                            @if($category->description)
                                                <div class="text-xs text-[#698696] mt-0.5 line-clamp-1">{{ $category->description }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs text-[#698696] font-mono">
                                            {{ $category->slug }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-[#EBF3F6] text-[#243C4C] border border-[#CBDDE6]">
                                                {{ $category->courses_count }} Kursus
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                            <button type="button"
                                                    @click="openEditModal({ id: {{ $category->id }}, name: @js($category->name), description: @js($category->description) }, '{{ route('admin.categories.update', $category) }}')"
                                                    class="inline-flex items-center px-3 py-1.5 bg-slate-100 text-[#243C4C] hover:bg-slate-200 border border-slate-200 rounded-md text-xs font-semibold transition">
                                                Edit
                                            </button>

                                            <button type="button"
                                                    @click="openDeleteModal(@js($category->name), '{{ route('admin.categories.destroy', $category) }}')"
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 rounded-md text-xs font-semibold transition">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-sm text-[#698696]">
                                            Belum ada kategori yang tersedia.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($categories->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100">
                            {{ $categories->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal Edit Kategori (Arctic Reflection Style) -->
        <div x-show="editModalOpen"
             x-cloak
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-edit-category-title"
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
                     class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-[#ACBCBF]/50">
                    <div class="bg-[#243C4C] px-6 py-4 flex items-center justify-between">
                        <h3 class="text-base font-bold text-white" id="modal-edit-category-title">
                            Edit Kategori Kursus
                        </h3>
                        <button type="button" @click="editModalOpen = false" class="text-slate-300 hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form method="POST" :action="editCategory.action" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="edit_category_name" class="block font-medium text-sm text-gray-700">
                                Nama Kategori <span class="text-rose-500 font-bold">*</span>
                            </label>
                            <input id="edit_category_name" type="text" name="name" x-model="editCategory.name" required
                                   class="mt-1 block w-full border-gray-300 focus:border-[#5289AD] focus:ring-[#5289AD] rounded-md shadow-xs text-sm">
                        </div>

                        <div>
                            <label for="edit_category_description" class="block font-medium text-sm text-gray-700">
                                Deskripsi (Opsional)
                            </label>
                            <textarea id="edit_category_description" name="description" rows="3" x-model="editCategory.description"
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

        <!-- Custom Modal Konfirmasi Hapus Kategori (Arctic Reflection Style) -->
        <div x-show="deleteModalOpen"
             x-cloak
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-delete-category-title"
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
                                <h3 class="text-base font-bold text-[#243C4C]" id="modal-delete-category-title">
                                    Konfirmasi Hapus Kategori
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-[#698696]">
                                        Apakah Anda yakin ingin menghapus kategori <span class="font-bold text-[#243C4C]" x-text="deleteTargetName"></span>? Tindakan ini tidak dapat dibatalkan.
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
</x-app-layout>
