<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="text-xl font-semibold text-text-dark leading-tight">
                {{ __('Manajemen Tugas') }}
            </h2>
            <div class="text-sm text-gray-600">
                {{ __('Pelajaran: ') . $lesson->title }}
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-background">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-6 text-sm text-gray-600">
                <a href="{{ route('courses.modules.index', $module->course) }}" class="hover:text-gray-900">Course</a>
                <span class="mx-2">/</span>
                <a href="{{ route('modules.lessons.index', $module) }}" class="hover:text-gray-900">{{ $module->title }}</a>
                <span class="mx-2">/</span>
                <a href="{{ route('learning.lesson', [$module->course, $module, $lesson]) }}" class="hover:text-gray-900">{{ $lesson->title }}</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900">Tugas</span>
            </nav>

            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Tugas</h3>
                <a href="{{ route('modules.lessons.assignments.create', [$module, $lesson]) }}" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-tertiary to-secondary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:opacity-95">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Tugas
                </a>
            </div>

            @if($assignments->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <p class="mt-4 text-gray-600">Belum ada tugas untuk pelajaran ini.</p>
                    <a href="{{ route('modules.lessons.assignments.create', [$module, $lesson]) }}" class="mt-4 inline-flex items-center text-sm font-semibold text-tertiary hover:text-secondary">
                        Tambah tugas pertama &rarr;
                    </a>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenggat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submission</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($assignments as $a)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $a->title }}</div>
                                        <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($a->description, 50) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($a->due_date)
                                            <span class="text-sm text-gray-900">{{ $a->due_date->format('d M Y, H:i') }}</span>
                                            @if($a->due_date->isPast() && !$a->due_date->isPast())
                                                <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Hampir lewat</span>
                                            @endif
                                        @else
                                            <span class="text-sm text-gray-400">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $a->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $a->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('modules.lessons.assignments.submissions.index', [$module, $lesson, $a]) }}" class="text-sm text-tertiary hover:text-secondary font-medium">
                                            Lihat {{ $a->submissions()->count() }} submission
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('modules.lessons.assignments.edit', [$module, $lesson, $a]) }}" class="text-tertiary hover:text-secondary mr-3">Edit</a>
                                        <form action="{{ route('modules.lessons.assignments.destroy', [$module, $lesson, $a]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tugas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="mt-6">
                <a href="{{ route('learning.lesson', [$module->course, $module, $lesson]) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                    &larr; Kembali ke pelajaran
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
