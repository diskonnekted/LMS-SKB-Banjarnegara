<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Jawaban Siswa
                </h2>
                <div class="text-sm text-gray-500">
                    {{ $attempt->quiz->title }} · {{ $attempt->user->name ?? '-' }}
                </div>
            </div>

            <a href="{{ route('teacher.quizzes.attempts.index', $attempt->quiz) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <div class="p-4 border rounded bg-gray-50">
                            <div class="text-xs text-gray-500">Skor</div>
                            <div class="text-2xl font-bold">{{ is_null($attempt->score) ? '-' : round($attempt->score) }}%</div>
                        </div>
                        <div class="p-4 border rounded bg-gray-50">
                            <div class="text-xs text-gray-500">Kelulusan</div>
                            <div class="text-2xl font-bold {{ $attempt->passed ? 'text-green-700' : 'text-red-700' }}">{{ $attempt->passed ? 'Lulus' : 'Tidak' }}</div>
                        </div>
                        <div class="p-4 border rounded bg-gray-50">
                            <div class="text-xs text-gray-500">Waktu</div>
                            <div class="text-lg font-semibold">{{ optional($attempt->created_at)->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>

                    @php
                        $questions = $attempt->quiz->questions;
                    @endphp

                    @php
                        $essayPendingCount = $questions
                            ->where('type', 'essay')
                            ->filter(function ($q) use ($answers) {
                                $row = $answers[$q->id] ?? null;
                                if (! $row) {
                                    return false;
                                }

                                return is_null($row->is_correct);
                            })
                            ->count();
                    @endphp

                    @if($questions->where('type', 'essay')->isNotEmpty())
                        <div class="mb-6 rounded border bg-yellow-50 p-4 text-sm text-yellow-800">
                            Esai menunggu penilaian: <span class="font-semibold">{{ $essayPendingCount }}</span>
                        </div>
                    @endif

                    @if($questions->isEmpty())
                        <div class="text-gray-500">Kuis ini belum memiliki soal.</div>
                    @else
                        <div class="space-y-6">
                            @foreach($questions as $index => $q)
                                @php
                                    $answerRow = $answers[$q->id] ?? null;
                                    $raw = $answerRow ? $answerRow->answer : null;
                                    $options = is_array($q->options) ? $q->options : (json_decode($q->options, true) ?? []);
                                    $submittedArray = null;
                                    if (is_string($raw) && in_array($q->type, ['multiple_response', 'matching', 'drag_drop', 'sequencing'], true)) {
                                        $decoded = json_decode($raw, true);
                                        if (json_last_error() === JSON_ERROR_NONE) {
                                            $submittedArray = $decoded;
                                        }
                                    }
                                @endphp
                                <div class="p-5 border rounded bg-gray-50">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="font-bold text-lg">
                                            {{ $index + 1 }}. {{ $q->question }}
                                            <span class="text-xs font-normal text-gray-500 ml-2">({{ ucfirst(str_replace('_', ' ', $q->type ?? 'multiple_choice')) }})</span>
                                        </div>
                                        @if(!is_null($answerRow) && !is_null($answerRow->is_correct))
                                            <div class="shrink-0">
                                                @if($answerRow->is_correct)
                                                    <span class="inline-flex items-center px-2 py-1 rounded bg-green-100 text-green-700">Benar</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded bg-red-100 text-red-700">Salah</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    @if($q->media_url)
                                        @php
                                            $isRemote = \Illuminate\Support\Str::startsWith($q->media_url, ['http://', 'https://', '/']);
                                            $url = $isRemote ? $q->media_url : \Illuminate\Support\Facades\Storage::disk('public')->url($q->media_url);
                                            $ext = pathinfo($url, PATHINFO_EXTENSION);
                                            $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                            $isAudio = in_array(strtolower($ext), ['mp3', 'wav', 'ogg']);
                                            $isVideo = in_array(strtolower($ext), ['mp4', 'webm']);
                                        @endphp

                                        <div class="mt-4">
                                            @if($isImage)
                                                <img src="{{ $url }}" alt="Question Media" class="max-w-md h-auto rounded border shadow-sm">
                                            @elseif($isAudio)
                                                <audio controls class="w-full max-w-md">
                                                    <source src="{{ $url }}">
                                                </audio>
                                            @elseif($isVideo)
                                                <video controls class="w-full max-w-md">
                                                    <source src="{{ $url }}">
                                                </video>
                                            @else
                                                <a href="{{ $url }}" target="_blank" class="text-blue-600 hover:underline">Lihat Media</a>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="mt-4">
                                        <div class="text-xs text-gray-500 mb-2">Jawaban Siswa</div>

                                        @if(is_null($answerRow))
                                            <div class="text-gray-500">Jawaban tidak tersimpan (attempt lama).</div>

                                        @elseif($q->type === 'essay')
                                            <div class="p-3 bg-white border rounded whitespace-pre-wrap">{{ $raw }}</div>

                                            @if(!is_null($answerRow))
                                                <div class="mt-3 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                                    <div class="text-sm text-gray-600">
                                                        Status: <span class="font-semibold">{{ is_null($answerRow->is_correct) ? 'Belum dinilai' : ($answerRow->is_correct ? 'Benar' : 'Salah') }}</span>
                                                    </div>
                                                    <form method="POST" action="{{ route('teacher.quiz-attempt-answers.grade', $answerRow) }}" class="flex items-center gap-2">
                                                        @csrf
                                                        <select name="is_correct" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                                            <option value="" disabled @selected(is_null($answerRow->is_correct))>Pilih penilaian</option>
                                                            <option value="1" @selected($answerRow->is_correct === true)>Benar</option>
                                                            <option value="0" @selected($answerRow->is_correct === false)>Salah</option>
                                                        </select>
                                                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-semibold">
                                                            Simpan
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif

                                        @elseif($q->type === 'multiple_choice' || !$q->type)
                                            @php
                                                $key = is_string($raw) ? $raw : null;
                                                $label = $key && isset($options[$key]) ? $options[$key] : null;
                                            @endphp
                                            <div class="p-3 bg-white border rounded">
                                                @if($key)
                                                    <div class="font-semibold">{{ strtoupper($key) }}{{ $label ? ' - '.$label : '' }}</div>
                                                @else
                                                    <div class="text-gray-500">(Kosong)</div>
                                                @endif
                                            </div>

                                        @elseif($q->type === 'multiple_response')
                                            @php
                                                $keys = is_array($submittedArray) ? $submittedArray : [];
                                            @endphp
                                            <div class="p-3 bg-white border rounded">
                                                @if(empty($keys))
                                                    <div class="text-gray-500">(Kosong)</div>
                                                @else
                                                    <ul class="list-disc list-inside">
                                                        @foreach($keys as $k)
                                                            <li>{{ strtoupper((string) $k) }}{{ isset($options[$k]) ? ' - '.$options[$k] : '' }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>

                                        @elseif($q->type === 'true_false')
                                            <div class="p-3 bg-white border rounded font-semibold">{{ $raw === 'true' ? 'True' : ($raw === 'false' ? 'False' : '-') }}</div>

                                        @elseif(in_array($q->type, ['short_answer', 'numeric'], true))
                                            <div class="p-3 bg-white border rounded whitespace-pre-wrap">{{ $raw }}</div>

                                        @elseif(in_array($q->type, ['matching', 'drag_drop'], true))
                                            <div class="p-3 bg-white border rounded">
                                                @php
                                                    $pairs = is_array($options) ? $options : [];
                                                    $picked = is_array($submittedArray) ? $submittedArray : [];
                                                @endphp
                                                @if(empty($pairs))
                                                    <div class="text-gray-500">(Tidak ada pasangan)</div>
                                                @else
                                                    <div class="space-y-2">
                                                        @foreach($pairs as $idx => $pair)
                                                            <div class="flex flex-col md:flex-row md:items-center gap-2">
                                                                <div class="flex-1 font-medium">{{ $pair['left'] ?? '-' }}</div>
                                                                <div class="text-gray-500">→</div>
                                                                <div class="flex-1">{{ $picked[$idx] ?? '-' }}</div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>

                                        @elseif($q->type === 'sequencing')
                                            @php
                                                $picked = is_array($submittedArray) ? $submittedArray : [];
                                                $correctOrder = json_decode($q->correct_answer, true) ?? [];
                                            @endphp
                                            <div class="p-3 bg-white border rounded">
                                                @if(empty($picked))
                                                    <div class="text-gray-500">(Kosong)</div>
                                                @else
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                        <div>
                                                            <div class="text-xs text-gray-500 mb-2">Urutan yang dipilih</div>
                                                            <ul class="list-disc list-inside">
                                                                @foreach($picked as $item => $order)
                                                                    <li>{{ $order }}. {{ $item }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                        <div>
                                                            <div class="text-xs text-gray-500 mb-2">Kunci urutan</div>
                                                            @if(empty($correctOrder))
                                                                <div class="text-gray-500">-</div>
                                                            @else
                                                                <ol class="list-decimal list-inside">
                                                                    @foreach($correctOrder as $item)
                                                                        <li>{{ $item }}</li>
                                                                    @endforeach
                                                                </ol>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="p-3 bg-white border rounded whitespace-pre-wrap">{{ $raw }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>
    <script>
        (function(){
            const container = document.querySelector('.mx-auto');
            if (container) {
                try {
                    renderMathInElement(container, {
                        delimiters: [
                            {left: "$$", right: "$$", display: true},
                            {left: "\\[", right: "\\]", display: true},
                            {left: "$", right: "$", display: false},
                            {left: "\\(", right: "\\)", display: false}
                        ],
                        throwOnError: false
                    });
                } catch(e){}
            }
        })();
    </script>
</x-app-layout>
