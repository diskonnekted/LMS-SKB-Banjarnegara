<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pelajaran') }}
            </h2>
            <a href="{{ route('courses.index') }}" class="text-gray-600 hover:text-gray-900">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Left Column: Image & Actions -->
                        <div class="w-full md:w-1/3 space-y-6">
                            @if($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full rounded-lg shadow-md object-cover aspect-video">
                            @else
                                <div class="w-full aspect-video bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">
                                    <span class="text-lg">Tidak Ada Gambar</span>
                                </div>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-500">Status</span>
                                <span class="px-3 py-1 text-sm rounded-full {{ $course->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $course->is_published ? 'Diterbitkan' : 'Draf' }}
                                </span>
                            </div>

                            <div class="flex flex-col gap-3">
                                @role('admin|teacher')
                                    <a href="{{ route('courses.edit', $course) }}" class="flex justify-center items-center w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                                        Edit Pelajaran
                                    </a>
                                    <a href="{{ route('courses.modules.index', $course) }}" class="flex justify-center items-center w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                                        Kelola Modul
                                    </a>
                                    @role('admin')
                                    <form action="{{ route('courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelajaran ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex justify-center items-center w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition">
                                            Hapus Pelajaran
                                        </button>
                                    </form>
                                    @endrole
                                @else
                                    @php
                                        $enrolled = auth()->check() ? auth()->user()->enrolledCourses()->where('course_id', $course->id)->exists() : false;
                                    @endphp
                                    @if($enrolled)
                                        <a href="{{ route('learning.course', $course) }}" class="flex justify-center items-center w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                                            Lanjut Belajar
                                        </a>
                                    @else
                                        <form action="{{ route('courses.enroll', $course) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="flex justify-center items-center w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                                                Daftar Sekarang
                                            </button>
                                        </form>
                                    @endif
                                @endrole
                            </div>
                        </div>

                        <!-- Right Column: Details & Modules -->
                        <div class="w-full md:w-2/3">
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $course->title }}</h1>
                            
                            <div class="prose max-w-none text-gray-600 mb-8">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Deskripsi</h3>
                                <div class="description-content">
                                    {!! \App\Helpers\ContentParser::parse($course->description) !!}
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <div class="rounded-xl border border-gray-200 bg-background p-5">
                                    <div class="flex items-center gap-2 mb-2">
                                        <x-heroicon name="academic-cap" class="h-5 w-5 text-tertiary" />
                                        <h3 class="text-base font-semibold text-text-dark">Capaian Pembelajaran (CP)</h3>
                                    </div>
                                    <div class="text-sm text-gray-700 whitespace-pre-line">
                                        {{ $course->basic_competency ?: 'Belum ditentukan.' }}
                                    </div>
                                </div>
                                <div class="rounded-xl border border-gray-200 bg-background p-5">
                                    <div class="flex items-center gap-2 mb-2">
                                        <x-heroicon name="rectangle-stack" class="h-5 w-5 text-secondary" />
                                        <h3 class="text-base font-semibold text-text-dark">Tujuan Pembelajaran</h3>
                                    </div>
                                    <div class="text-sm text-gray-700 whitespace-pre-line">
                                        {{ $course->learning_objectives ?: 'Belum ditentukan.' }}
                                    </div>
                                </div>
                            </div>

                            <div class="border-t pt-8">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-xl font-bold text-gray-900">Daftar Modul</h3>
                                    @role('admin|teacher')
                                        <a href="{{ route('courses.modules.index', $course) }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                            Kelola Modul &rarr;
                                        </a>
                                    @endrole
                                </div>

                                @if($course->modules->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($course->modules as $index => $module)
                                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <h4 class="font-bold text-lg text-gray-900">
                                                            Modul {{ $index + 1 }}: {{ $module->title }}
                                                        </h4>
                                                        <p class="text-sm text-gray-500 mt-1">
                                                            {{ $module->lessons->count() }} Pelajaran
                                                        </p>
                                                    </div>
                                                    @role('admin|teacher')
                                                        <a href="{{ route('courses.modules.index', $course) }}" class="px-3 py-1 bg-white border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">
                                                            Lihat Pelajaran
                                                        </a>
                                                    @else
                                                        @if($module->lessons->count() > 0)
                                                            @php
                                                                $firstLesson = $module->lessons->sortBy('order')->first();
                                                            @endphp
                                                            @if(auth()->check() && isset($enrolled) && $enrolled)
                                                                <a href="{{ route('learning.lesson', [$course, $module, $firstLesson]) }}" class="px-3 py-1 bg-indigo-600 text-white rounded text-sm hover:bg-indigo-700">
                                                                    Mulai Modul
                                                                </a>
                                                            @else
                                                                <span class="px-3 py-1 bg-gray-100 border border-gray-300 rounded text-sm text-gray-600">
                                                                    Modul terkunci
                                                                </span>
                                                            @endif
                                                        @endif
                                                    @endrole
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="bg-gray-50 border border-dashed border-gray-300 rounded-lg p-8 text-center">
                                        <p class="text-gray-500 mb-4">Belum ada modul yang ditambahkan ke pelajaran ini.</p>
                                        <a href="{{ route('courses.modules.index', $course) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Tambah Modul
                                        </a>
                                    </div>
                                @endif
                            </div>

                            @role('admin|teacher')
                            <div class="mt-12">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Daftar Siswa & Nilai</h3>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Kuis</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-rata Nilai</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Terbaru</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Terbaru</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($studentStats ?? [] as $stat)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $stat['user']->name }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $stat['user']->email }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $stat['progress'] }}%</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $stat['attempts'] }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $stat['avg'] !== null ? $stat['avg'] : '-' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $stat['latest'] !== null ? $stat['latest'] : '-' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        @if($stat['passed_latest'] === true)
                                                            <span class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Lulus</span>
                                                        @elseif($stat['passed_latest'] === false)
                                                            <span class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">Tidak Lulus</span>
                                                        @else
                                                            <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">Belum ada siswa terdaftar atau belum ada nilai kuis.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endrole
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
