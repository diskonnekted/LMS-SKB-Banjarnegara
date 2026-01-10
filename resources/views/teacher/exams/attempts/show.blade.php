<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Jawaban</h2>
                <div class="text-sm text-gray-500">{{ $exam->title }} • {{ $attempt->user->name }}</div>
            </div>
            <a href="{{ route('teacher.exams.attempts.index', $exam) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="rounded border p-4">
                            <div class="text-xs text-gray-500">Nilai</div>
                            <div class="text-2xl font-bold">{{ $attempt->score }}%</div>
                        </div>
                        <div class="rounded border p-4">
                            <div class="text-xs text-gray-500">Poin</div>
                            <div class="text-2xl font-bold">{{ $attempt->earned_points }} / {{ $attempt->total_points }}</div>
                        </div>
                        <div class="rounded border p-4">
                            <div class="text-xs text-gray-500">Status</div>
                            <div class="text-2xl font-bold">{{ $attempt->passed ? 'Lulus' : 'Tidak lulus' }}</div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach($attempt->answers->sortBy('question.order') as $ans)
                            <div class="border rounded p-4 bg-gray-50">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="font-semibold">{{ $ans->question->question }}</div>
                                    <div class="text-sm font-semibold">{{ $ans->earned_points }} / {{ $ans->question->points }} poin</div>
                                </div>
                                <div class="mt-2 text-sm">
                                    <div class="text-xs text-gray-500">Jawaban</div>
                                    <div class="font-mono break-words">{{ $ans->answer ?? '-' }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

