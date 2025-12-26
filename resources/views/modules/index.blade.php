<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Konten Pelajaran: ') . $course->title }}
            </h2>
            <a href="{{ route('courses.index') }}" class="text-gray-500 hover:text-gray-700">{{ __('Kembali ke Pelajaran') }}</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-auth-session-status class="mb-4" :status="session('success')" />

            <!-- Add Module Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('courses.modules.store', $course) }}" method="POST" class="flex gap-4">
                        @csrf
                        <input type="text" name="title" placeholder="{{ __('Judul Modul Baru') }}" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Tambah Modul') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Modules List -->
            <div class="space-y-4">
                @foreach($course->modules as $module)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-gray-800">{{ $module->title }}</h3>
                                <div class="flex items-center space-x-2">
                                    <form action="{{ route('modules.destroy', $module) }}" method="POST" onsubmit="return confirm('{{ __('Apakah Anda yakin?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm">{{ __('Hapus Modul') }}</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Lessons List -->
                            <ul class="divide-y divide-gray-200 ml-4">
                                @foreach($module->lessons as $lesson)
                                    <li class="flex justify-between items-center bg-gray-50 p-3">
                                        <div class="flex items-center">
                                            <span class="mr-2 text-gray-500">
                                                @if($lesson->type == 'video')
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                @elseif($lesson->type == 'pdf')
                                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                @elseif($lesson->type == 'text')
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                @elseif($lesson->type == 'doc')
                                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                @elseif($lesson->type == 'xls')
                                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                @elseif($lesson->type == 'ppt')
                                                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                                                @else
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                @endif
                                            </span>
                                            <span class="text-gray-700 font-medium">{{ $lesson->title }}</span>
                                            @if($lesson->quiz)
                                                <span class="ml-3 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                                                    Kuis
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            @if($lesson->quiz)
                                                <a href="{{ route('quizzes.edit', $lesson->quiz) }}" class="px-3 py-1.5 rounded-md bg-purple-600 text-white text-sm hover:bg-purple-700">
                                                    {{ __('Edit Kuis') }}
                                                </a>
                                            @else
                                                <a href="{{ route('lessons.quizzes.create', $lesson) }}" class="px-3 py-1.5 rounded-md bg-purple-600 text-white text-sm hover:bg-purple-700">
                                                    {{ __('Buat Kuis') }}
                                                </a>
                                            @endif
                                            <a href="{{ route('lessons.edit', $lesson) }}" class="px-3 py-1.5 rounded-md bg-blue-600 text-white text-sm hover:bg-blue-700">
                                                {{ __('Edit') }}
                                            </a>
                                            <form action="{{ route('lessons.destroy', $lesson) }}" method="POST" onsubmit="return confirm('{{ __('Hapus pelajaran?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 rounded-md bg-red-600 text-white text-sm hover:bg-red-700">
                                                    {{ __('Hapus') }}
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="mt-4 ml-4">
                                <a href="{{ route('modules.lessons.create', $module) }}" class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    {{ __('Tambah Pelajaran') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
