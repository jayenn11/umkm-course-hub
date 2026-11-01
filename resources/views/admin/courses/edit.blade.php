<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2 text-sm text-[#698696]">
                <a href="{{ route('admin.courses.index') }}" class="text-[#5289AD] hover:text-[#243C4C] font-medium transition">Kelola Kursus</a>
                <span>/</span>
                <span class="text-[#243C4C] font-semibold truncate">Edit: {{ $course->title }}</span>
            </div>
            <a href="{{ route('admin.courses.lessons.index', $course) }}"
               class="inline-flex items-center px-3.5 py-2 bg-[#EBF3F6] text-[#243C4C] border border-[#CBDDE6] rounded-md text-xs font-semibold hover:bg-[#dbe9ef] transition">
                Kelola Materi Kursus &rarr;
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-xs border border-gray-200 p-6 sm:p-8">
                <h3 class="text-lg font-bold text-[#243C4C] mb-6">Perbarui Data Kursus</h3>

                <form method="POST" action="{{ route('admin.courses.update', $course) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="title">
                            {{ __('Judul Kursus') }} <span class="text-rose-500 font-bold">*</span>
                        </x-input-label>
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $course->title)" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="category_id">
                            {{ __('Kategori Kursus') }} <span class="text-rose-500 font-bold">*</span>
                        </x-input-label>
                        <select id="category_id" name="category_id" required
                                class="mt-1 block w-full border-gray-300 focus:border-[#5289AD] focus:ring-[#5289AD] rounded-md shadow-xs text-sm">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $course->category_id) == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="thumbnail" :value="__('URL Gambar Thumbnail (Opsional)')" />
                        <x-text-input id="thumbnail" name="thumbnail" type="text" class="mt-1 block w-full" :value="old('thumbnail', $course->thumbnail)" placeholder="https://contoh.com/gambar-banner.jpg" />
                        <x-input-error :messages="$errors->get('thumbnail')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description">
                            {{ __('Deskripsi Lengkap Kursus') }} <span class="text-rose-500 font-bold">*</span>
                        </x-input-label>
                        <textarea id="description" name="description" rows="5" required
                                  class="mt-1 block w-full border-gray-300 focus:border-[#5289AD] focus:ring-[#5289AD] rounded-md shadow-xs text-sm">{{ old('description', $course->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="flex items-center">
                        <input id="is_published" name="is_published" type="checkbox" value="1"
                               @checked(old('is_published', $course->is_published))
                               class="rounded border-gray-300 text-[#243C4C] shadow-xs focus:ring-[#5289AD]">
                        <label for="is_published" class="ms-2 text-sm font-medium text-gray-700">
                            Publikasikan kursus (tampil di katalog publik)
                        </label>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.courses.index') }}"
                           class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Simpan Perubahan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
