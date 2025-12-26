<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pelajaran: ') . $lesson->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('lessons.update', $lesson) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Judul Pelajaran</label>
                            <input type="text" name="title" value="{{ $lesson->title }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Tipe</label>
                            <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="video" {{ $lesson->type == 'video' ? 'selected' : '' }}>Video</option>
                                <option value="text" {{ $lesson->type == 'text' ? 'selected' : '' }}>Teks / Artikel</option>
                                <option value="pdf" {{ $lesson->type == 'pdf' ? 'selected' : '' }}>Dokumen PDF</option>
                                <option value="doc" {{ $lesson->type == 'doc' ? 'selected' : '' }}>Dokumen Word</option>
                                <option value="xls" {{ $lesson->type == 'xls' ? 'selected' : '' }}>Dokumen Excel</option>
                                <option value="ppt" {{ $lesson->type == 'ppt' ? 'selected' : '' }}>PowerPoint / Presentasi</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Konten / URL / Kode Embed</label>
                            <textarea name="content" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $lesson->content }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">File Saat Ini</label>
                            @if($lesson->file_path)
                                <p class="text-sm text-gray-500 mb-2">
                                    <a href="{{ asset('storage/' . $lesson->file_path) }}" target="_blank" class="text-indigo-600 hover:underline">Lihat File</a>
                                </p>
                            @else
                                <p class="text-sm text-gray-500 mb-2">Belum ada file diunggah.</p>
                            @endif
                            <label class="block text-sm font-medium text-gray-700 mt-2">Unggah/Ganti File (PDF/Word/Excel/PPT/Video)</label>
                            <input type="file" name="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-500 file:text-white hover:file:bg-indigo-600">
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('courses.modules.index', $lesson->module->course) }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900">Batal</a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Perbarui Pelajaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
