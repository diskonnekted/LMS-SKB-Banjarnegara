<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ujian Mandiri</h2>
            <a href="{{ route('teacher.exams.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                Buat Ujian
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-auth-session-status class="mb-4" :status="session('success')" />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($exams->isEmpty())
                        <div class="text-gray-500">Belum ada ujian.</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="text-left text-gray-600 border-b">
                                        <th class="py-2 pr-4">Judul</th>
                                        <th class="py-2 pr-4">Kode</th>
                                        <th class="py-2 pr-4">Soal</th>
                                        <th class="py-2 pr-4">KKM</th>
                                        <th class="py-2 pr-4">Status</th>
                                        <th class="py-2">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($exams as $exam)
                                        <tr class="border-b">
                                            <td class="py-3 pr-4">
                                                <div class="font-semibold">{{ $exam->title }}</div>
                                                <div class="text-xs text-gray-500">{{ $exam->created_at?->format('d/m/Y H:i') }}</div>
                                            </td>
                                            <td class="py-3 pr-4 font-mono">{{ $exam->access_code }}</td>
                                            <td class="py-3 pr-4">{{ $exam->questions_count }}</td>
                                            <td class="py-3 pr-4">{{ $exam->passing_score }}%</td>
                                            <td class="py-3 pr-4">
                                                @if($exam->is_published)
                                                    <span class="inline-flex items-center px-2 py-1 rounded bg-green-50 text-green-700 text-xs font-semibold">Published</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded bg-gray-100 text-gray-700 text-xs font-semibold">Draft</span>
                                                @endif
                                            </td>
                                            <td class="py-3">
                                                <div class="flex flex-wrap items-center gap-3">
                                                    <a href="{{ route('teacher.exams.edit', $exam) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">Kelola</a>
                                                    <a href="{{ route('exams.take', $exam->access_code) }}" class="text-blue-600 hover:text-blue-800 font-semibold" target="_blank" rel="noopener">Link Siswa</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">{{ $exams->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

