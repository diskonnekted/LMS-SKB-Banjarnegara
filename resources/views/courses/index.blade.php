<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                @role('admin|teacher')
                    {{ __('Pelajaran Saya') }}
                @else
                    {{ __('Pelajaran') }}
                @endrole
            </h2>
            
            <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">
                <!-- Search Filter -->
                <form action="{{ route('courses.index') }}" method="GET" class="w-full md:w-auto">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pelajaran..." class="block w-full md:w-64 pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm shadow-sm">
                    </div>
                </form>

                @role('admin|teacher')
                    <a href="{{ route('courses.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm transition-colors whitespace-nowrap">
                        + Buat Pelajaran
                    </a>
                @endrole
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($courses->isEmpty())
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada pelajaran</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        @if(request('search'))
                                            Tidak ditemukan pelajaran dengan kata kunci "{{ request('search') }}".
                                        @else
                                            @role('admin|teacher')
                                                Mulai dengan membuat pelajaran baru.
                                            @else
                                                Belum ada pelajaran dipublikasikan.
                                            @endrole
                                        @endif
                                    </p>
                                    @if(request('search'))
                                        <div class="mt-6">
                                            <a href="{{ route('courses.index') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">
                                                Hapus filter
                                            </a>
                                        </div>
                                    @else
                                        @role('admin|teacher')
                                            <div class="mt-6">
                                                <a href="{{ route('courses.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    + Buat Pelajaran
                                                </a>
                                            </div>
                                        @endrole
                                    @endif
                                </div>
                    @else
                        @php
                            $groupedCourses = $courses->groupBy(function($course) {
                                return $course->grade_level ?: 'Umum / Semua Kelas';
                            });
                        @endphp

                        <div class="space-y-12">
                            @foreach($groupedCourses as $gradeLevel => $gradeCourses)
                                <div class="bg-gray-50/50 rounded-2xl p-6 border border-gray-100">
                                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-3 mb-6 flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="w-3.5 h-6 bg-indigo-600 rounded-md"></span>
                                            <span>{{ $gradeLevel }}</span>
                                        </div>
                                        <span class="text-sm font-semibold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">{{ $gradeCourses->count() }} Pelajaran</span>
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        @foreach($gradeCourses as $course)
                                            <div class="bg-white border rounded-xl p-4 flex flex-col hover:shadow-lg transition-all duration-300">
                                                <div class="relative w-full h-40 mb-4 overflow-hidden rounded-md bg-gray-100 group">
                                                    @if($course->thumbnail)
                                                        <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <div class="absolute top-2 right-2">
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full shadow-sm {{ $course->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                            {{ $course->is_published ? 'Diterbitkan' : 'Draf' }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="mb-2">
                                                    <span class="text-xs font-bold text-indigo-600 uppercase tracking-wide">
                                                        {{ $course->category->name ?? 'Umum' }}
                                                    </span>
                                                </div>

                                                <h3 class="text-lg font-bold mb-2 text-gray-900 line-clamp-1" title="{{ $course->title }}">{{ $course->title }}</h3>
                                                <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ \App\Helpers\ContentParser::excerpt($course->description, 150) }}</p>
                                                
                                                <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center">
                                                    @role('admin|teacher')
                                                        <a href="{{ route('courses.modules.index', $course) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                            </svg>
                                                            Modul
                                                        </a>
                                                        <div class="flex items-center gap-3">
                                                            <a href="{{ route('courses.edit', $course) }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">Edit</a>
                                                            @if(auth()->user()->hasRole('admin') || auth()->user()->id === $course->teacher_id)
                                                                <form action="{{ route('courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelajaran ini?');" class="inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Hapus</button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <a href="{{ route('courses.show', $course) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Lihat Pelajaran</a>
                                                        <span class="text-sm text-gray-500">{{ $course->teacher->name }}</span>
                                                    @endrole
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
