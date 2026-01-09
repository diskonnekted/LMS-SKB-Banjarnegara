<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('courses.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Judul Pelajaran</label>
                            <input type="text" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Kategori Mata Pelajaran</label>
                            <select name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Kelas</label>
                            <select name="grade_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Pilih Kelas</option>
                                <optgroup label="Kesetaraan Paket A">
                                    <option value="Kesetaraan Paket A Kelas 3" @selected(old('grade_level')=='Kesetaraan Paket A Kelas 3')>Kelas 3</option>
                                    <option value="Kesetaraan Paket A Kelas 4" @selected(old('grade_level')=='Kesetaraan Paket A Kelas 4')>Kelas 4</option>
                                    <option value="Kesetaraan Paket A Kelas 5" @selected(old('grade_level')=='Kesetaraan Paket A Kelas 5')>Kelas 5</option>
                                    <option value="Kesetaraan Paket A Kelas 6" @selected(old('grade_level')=='Kesetaraan Paket A Kelas 6')>Kelas 6</option>
                                </optgroup>
                                <optgroup label="Kesetaraan Paket B">
                                    <option value="Kesetaraan Paket B Kelas 7" @selected(old('grade_level')=='Kesetaraan Paket B Kelas 7')>Kelas 7</option>
                                    <option value="Kesetaraan Paket B Kelas 8" @selected(old('grade_level')=='Kesetaraan Paket B Kelas 8')>Kelas 8</option>
                                    <option value="Kesetaraan Paket B Kelas 9" @selected(old('grade_level')=='Kesetaraan Paket B Kelas 9')>Kelas 9</option>
                                </optgroup>
                                <optgroup label="Kesetaraan Paket C">
                                    <option value="Kesetaraan Paket C Kelas 10" @selected(old('grade_level')=='Kesetaraan Paket C Kelas 10')>Kelas 10</option>
                                    <option value="Kesetaraan Paket C Kelas 11" @selected(old('grade_level')=='Kesetaraan Paket C Kelas 11')>Kelas 11</option>
                                    <option value="Kesetaraan Paket C Kelas 12" @selected(old('grade_level')=='Kesetaraan Paket C Kelas 12')>Kelas 12</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>
                        <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Capaian Pembelajaran (CP)</label>
                                <textarea name="basic_competency" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Tuliskan CP yang terkait dengan pelajaran ini"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tujuan Pembelajaran</label>
                                <textarea name="learning_objectives" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Setelah mempelajari pelajaran ini, siswa mampu..."></textarea>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Gambar Sampul</label>
                            <input type="file" name="thumbnail" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600">
                        </div>
                        <div class="mb-4 flex items-center">
                            <input type="checkbox" name="is_published" id="is_published" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <label for="is_published" class="ml-2 block text-sm text-gray-900">Terbitkan Segera</label>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Buat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
