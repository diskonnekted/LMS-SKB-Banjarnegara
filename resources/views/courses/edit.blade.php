<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('courses.update', $course) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Judul Pelajaran</label>
                            <input type="text" name="title" value="{{ old('title', $course->title) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Kategori Mata Pelajaran</label>
                            <select name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (old('category_id', $course->category_id) == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Kelas</label>
                            <select name="grade_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Pilih Kelas</option>
                                <optgroup label="Sekolah Dasar (SD)">
                                    <option value="Kelas 3 SD" {{ (old('grade_level', $course->grade_level) == 'Kelas 3 SD') ? 'selected' : '' }}>Kelas 3 SD</option>
                                    <option value="Kelas 4 SD" {{ (old('grade_level', $course->grade_level) == 'Kelas 4 SD') ? 'selected' : '' }}>Kelas 4 SD</option>
                                    <option value="Kelas 5 SD" {{ (old('grade_level', $course->grade_level) == 'Kelas 5 SD') ? 'selected' : '' }}>Kelas 5 SD</option>
                                    <option value="Kelas 6 SD" {{ (old('grade_level', $course->grade_level) == 'Kelas 6 SD') ? 'selected' : '' }}>Kelas 6 SD</option>
                                </optgroup>
                                <optgroup label="Sekolah Menengah Pertama (SMP)">
                                    <option value="Kelas 7 SMP" {{ (old('grade_level', $course->grade_level) == 'Kelas 7 SMP') ? 'selected' : '' }}>Kelas 7 SMP</option>
                                    <option value="Kelas 8 SMP" {{ (old('grade_level', $course->grade_level) == 'Kelas 8 SMP') ? 'selected' : '' }}>Kelas 8 SMP</option>
                                    <option value="Kelas 9 SMP" {{ (old('grade_level', $course->grade_level) == 'Kelas 9 SMP') ? 'selected' : '' }}>Kelas 9 SMP</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $course->description) }}</textarea>
                        </div>
                        <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kompetensi Dasar (KD)</label>
                                <textarea name="basic_competency" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('basic_competency', $course->basic_competency) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tujuan Pembelajaran</label>
                                <textarea name="learning_objectives" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('learning_objectives', $course->learning_objectives) }}</textarea>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Gambar Sampul</label>
                            @if($course->thumbnail)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Thumbnail Saat Ini" class="h-32 w-auto object-cover rounded">
                                </div>
                            @endif
                            <input type="file" name="thumbnail" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600">
                        </div>
                        <div class="mb-4 flex items-center">
                            <input type="checkbox" name="is_published" id="is_published" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ $course->is_published ? 'checked' : '' }}>
                            <label for="is_published" class="ml-2 block text-sm text-gray-900">Diterbitkan</label>
                        </div>
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('courses.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Perbarui
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
