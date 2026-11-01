<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2 text-sm text-[#698696]">
                <a href="{{ route('admin.courses.index') }}" class="text-[#5289AD] hover:text-[#243C4C] font-medium transition">Kelola Kursus</a>
                <span>/</span>
                <a href="{{ route('admin.courses.lessons.index', $course) }}" class="text-[#5289AD] hover:text-[#243C4C] font-medium truncate transition">{{ $course->title }}</a>
                <span>/</span>
                <span class="text-[#243C4C] font-semibold truncate">Edit: {{ $lesson->title }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-xs border border-gray-200 p-6 sm:p-8">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-[#243C4C]">Edit Materi Pembelajaran</h3>
                    <p class="text-xs text-[#698696] mt-1">Kursus: <span class="font-semibold text-[#243C4C]">{{ $course->title }}</span></p>
                </div>

                <form method="POST" action="{{ route('admin.courses.lessons.update', [$course, $lesson]) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="title">
                            {{ __('Judul Materi / Bab') }} <span class="text-rose-500 font-bold">*</span>
                        </x-input-label>
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $lesson->title)" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="video_url" :value="__('URL Video Embed (YouTube / Vimeo - Opsional)')" />
                        <x-text-input id="video_url" name="video_url" type="url" class="mt-1 block w-full" :value="old('video_url', $lesson->video_url)" placeholder="https://www.youtube.com/embed/dQw4w9WgXcQ" />
                        <p class="mt-1 text-xs text-[#698696]">Gunakan format URL embed (contoh: <code>https://www.youtube.com/embed/ID_VIDEO</code>).</p>
                        <x-input-error :messages="$errors->get('video_url')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="content" :value="__('Isi Materi / Catatan Bacaan (Opsional)')" />
                        <textarea id="content" name="content" rows="6"
                                  class="mt-1 block w-full border-gray-300 focus:border-[#5289AD] focus:ring-[#5289AD] rounded-md shadow-xs text-sm">{{ old('content', $lesson->content) }}</textarea>
                        <x-input-error :messages="$errors->get('content')" class="mt-2" />
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.courses.lessons.index', $course) }}"
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
