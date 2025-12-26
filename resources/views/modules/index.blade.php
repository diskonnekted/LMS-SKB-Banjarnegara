<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Konten Kursus: ') . $course->title }}
            </h2>
            <a href="{{ route('courses.index') }}" class="text-gray-500 hover:text-gray-700">{{ __('Kembali ke Kursus') }}</a>
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
                                                @if($lesson->type == 'video') 🎥
                                                @elseif($lesson->type == 'pdf') 📄
                                                @elseif($lesson->type == 'text') 📝
                                                @elseif($lesson->type == 'doc') 📝
                                                @elseif($lesson->type == 'xls') 📊
                                                @elseif($lesson->type == 'ppt') 🎞️
                                                @else 📁 @endif
                                            </span>
                                            <span class="text-gray-700">{{ $lesson->title }}</span>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('lessons.edit', $lesson) }}" class="text-blue-500 hover:underline text-sm">{{ __('Edit') }}</a>
                                            <form action="{{ route('lessons.destroy', $lesson) }}" method="POST" onsubmit="return confirm('{{ __('Hapus pelajaran?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm">{{ __('Hapus') }}</button>
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
