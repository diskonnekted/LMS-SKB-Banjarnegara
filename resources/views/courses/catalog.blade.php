<x-public-layout>
    <section class="pt-28 pb-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Semua Pelajaran</h1>
                    <p class="mt-2 text-gray-600">Jelajahi seluruh pelajaran yang tersedia.</p>
                </div>
                <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200">
                    Kembali ke Beranda
                </a>
            </div>

            <form action="{{ route('courses.catalog') }}" method="GET" class="bg-gray-50 border border-gray-200 rounded-2xl p-4 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Cari judul atau deskripsi">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                        <select name="category_id" class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(request('category_id')==$cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <select name="grade_level" class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua</option>
                            @foreach($gradeLevels as $gl)
                                <option value="{{ $gl }}" @selected(request('grade_level')==$gl)>{{ $gl }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex justify-end gap-3">
                    <a href="{{ route('courses.catalog') }}" class="px-4 py-2 rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200">Reset</a>
                    <button type="submit" class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">Terapkan</button>
                </div>
            </form>

            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @forelse($courses as $course)
                    <div class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-300 border border-gray-100">
                        <div class="relative h-48 overflow-hidden">
                            @if($course->thumbnail)
                                <img class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700" src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}">
                            @else
                                <img class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700" src="{{ asset('images/skb2.jpg') }}" alt="{{ $course->title }}">
                            @endif
                            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-indigo-600 shadow-lg">
                                PELAJARAN
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-3">
                                @if($course->teacher && $course->teacher->avatar)
                                    <img src="{{ Storage::url($course->teacher->avatar) }}" alt="{{ $course->teacher->name }}" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600 border border-gray-200">
                                        {{ substr($course->teacher->name ?? 'T', 0, 1) }}
                                    </div>
                                @endif
                                <span class="text-xs font-medium text-gray-500">{{ $course->teacher->name ?? 'Instruktur' }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1 line-clamp-1 group-hover:text-indigo-600 transition-colors">{{ $course->title }}</h3>
                            <p class="text-sm text-gray-600 line-clamp-2 mb-4">{{ Str::limit(strip_tags($course->description), 150) }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">{{ $course->grade_level }}</span>
                                <a href="{{ route('courses.show', $course) }}" class="px-3 py-1.5 rounded-md bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">
                                    Lihat Pelajaran
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-12 text-center bg-white rounded-3xl border border-dashed border-gray-300">
                        <p class="text-gray-500">Tidak ada pelajaran ditemukan.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $courses->links() }}
            </div>
        </div>
    </section>
</x-public-layout>

