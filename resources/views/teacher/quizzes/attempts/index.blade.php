<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Jawaban Siswa: {{ $quiz->title }}
                </h2>
                <div class="text-sm text-gray-500">
                    {{ $quiz->lesson->module->course->title }}
                </div>
            </div>

            <a href="{{ route('quizzes.edit', $quiz) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                Kelola Kuis
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($attempts->isEmpty())
                        <div class="text-gray-500">Belum ada jawaban yang masuk.</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="text-left text-gray-600 border-b">
                                        <th class="py-3 pr-4">Siswa</th>
                                        <th class="py-3 pr-4">Email</th>
                                        <th class="py-3 pr-4">Skor</th>
                                        <th class="py-3 pr-4">Lulus</th>
                                        <th class="py-3 pr-4">Waktu</th>
                                        <th class="py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @foreach($attempts as $attempt)
                                        <tr>
                                            <td class="py-3 pr-4 font-medium">{{ $attempt->user->name ?? '-' }}</td>
                                            <td class="py-3 pr-4 text-gray-600">{{ $attempt->user->email ?? '-' }}</td>
                                            <td class="py-3 pr-4">{{ is_null($attempt->score) ? '-' : round($attempt->score) }}%</td>
                                            <td class="py-3 pr-4">
                                                @if($attempt->passed)
                                                    <span class="inline-flex items-center px-2 py-1 rounded bg-green-100 text-green-700">Ya</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded bg-red-100 text-red-700">Tidak</span>
                                                @endif
                                            </td>
                                            <td class="py-3 pr-4 text-gray-600">{{ optional($attempt->created_at)->format('d/m/Y H:i') }}</td>
                                            <td class="py-3">
                                                <a href="{{ route('teacher.quizzes.attempts.show', $attempt) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                                                    Lihat Jawaban
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $attempts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

