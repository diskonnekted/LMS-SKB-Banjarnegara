<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $exam->title }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($exam->description)
                        <div class="mb-4 text-gray-700">{{ $exam->description }}</div>
                    @endif

                    <div class="mb-4 text-sm text-gray-600">
                        KKM: <span class="font-semibold">{{ $exam->passing_score }}%</span>
                        @if($exam->duration_minutes)
                            • Durasi: <span class="font-semibold">{{ $exam->duration_minutes }} menit</span>
                        @endif
                    </div>

                    @if($questions->isEmpty())
                        <div class="text-gray-500">Ujian belum memiliki soal.</div>
                    @else
                        <form method="POST" action="{{ route('exams.submit', $exam->access_code) }}" class="space-y-6">
                            @csrf

                            @foreach($questions as $index => $question)
                                <div class="border rounded p-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="font-semibold">{{ $index + 1 }}. {!! nl2br(e($question->question)) !!}</div>
                                        <div class="text-sm text-gray-600">{{ $question->points }} poin</div>
                                    </div>

                                    @if($question->media_url)
                                        <div class="mt-3">
                                            <a href="{{ $question->media_url }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold" target="_blank" rel="noopener">Buka media</a>
                                        </div>
                                    @endif

                                    <div class="mt-4 space-y-2">
                                        @php $name = 'q_'.$question->id; @endphp

                                        @if($question->type === 'multiple_choice')
                                            @foreach(($question->options ?? []) as $key => $val)
                                                <label class="flex items-start gap-3">
                                                    <input type="radio" name="{{ $name }}" value="{{ $key }}" class="mt-1 rounded-full border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" required>
                                                    <div>
                                                        <div class="font-semibold">{{ strtoupper($key) }}</div>
                                                        <div class="text-gray-700">{!! nl2br(e($val)) !!}</div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        @elseif($question->type === 'multiple_response')
                                            @foreach(($question->options ?? []) as $key => $val)
                                                <label class="flex items-start gap-3">
                                                    <input type="checkbox" name="{{ $name }}[]" value="{{ $key }}" class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                    <div>
                                                        <div class="font-semibold">{{ strtoupper($key) }}</div>
                                                        <div class="text-gray-700">{!! nl2br(e($val)) !!}</div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        @elseif($question->type === 'true_false')
                                            <label class="flex items-center gap-3">
                                                <input type="radio" name="{{ $name }}" value="true" class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" required>
                                                <span>True</span>
                                            </label>
                                            <label class="flex items-center gap-3">
                                                <input type="radio" name="{{ $name }}" value="false" class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" required>
                                                <span>False</span>
                                            </label>
                                        @elseif(in_array($question->type, ['short_answer','numeric'], true))
                                            <input type="text" name="{{ $name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            <div class="flex justify-end">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Kirim Jawaban</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

