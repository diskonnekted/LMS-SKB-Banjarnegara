<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Jawaban Siswa</h2>
                <div class="text-sm text-gray-500">{{ $exam->title }}</div>
            </div>
            <a href="{{ route('teacher.exams.edit', $exam) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($attempts->isEmpty())
                        <div class="text-gray-500">Belum ada jawaban.</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="text-left text-gray-600 border-b">
                                        <th class="py-2 pr-4">Siswa</th>
                                        <th class="py-2 pr-4">Nilai</th>
                                        <th class="py-2 pr-4">Poin</th>
                                        <th class="py-2 pr-4">Status</th>
                                        <th class="py-2">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attempts as $attempt)
                                        <tr class="border-b">
                                            <td class="py-3 pr-4">
                                                <div class="font-semibold">{{ $attempt->user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $attempt->created_at?->format('d/m/Y H:i') }}</div>
                                            </td>
                                            <td class="py-3 pr-4">{{ $attempt->score }}%</td>
                                            <td class="py-3 pr-4">{{ $attempt->earned_points }} / {{ $attempt->total_points }}</td>
                                            <td class="py-3 pr-4">
                                                @if($attempt->passed)
                                                    <span class="inline-flex items-center px-2 py-1 rounded bg-green-50 text-green-700 text-xs font-semibold">Lulus</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded bg-red-50 text-red-700 text-xs font-semibold">Tidak lulus</span>
                                                @endif
                                            </td>
                                            <td class="py-3">
                                                <a href="{{ route('teacher.exams.attempts.show', $attempt) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $attempts->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

