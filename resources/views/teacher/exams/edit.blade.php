<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Ujian: {{ $exam->title }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-auth-session-status class="mb-4" :status="session('success')" />

            @if ($errors->any())
                <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <h3 class="text-lg font-bold">Detail Ujian</h3>
                        <div class="flex items-center gap-3">
                            <a href="#questionFormSection" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-indigo-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">Tambah Soal</a>
                            <a href="{{ $baseUrl . route('teacher.exams.attempts.index', $exam, false) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">Lihat Jawaban Siswa</a>
                            <form action="{{ $baseUrl . route('teacher.exams.destroy', $exam, false) }}" method="POST" onsubmit="return confirm('Hapus ujian ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-red-300 rounded-md font-semibold text-xs text-red-700 uppercase tracking-widest shadow-sm hover:bg-red-50">Hapus</button>
                            </form>
                        </div>
                    </div>

                    <form id="examForm" method="POST" action="{{ $baseUrl . route('teacher.exams.update', $exam, false) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Judul</label>
                            <input type="text" name="title" value="{{ old('title', $exam->title) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $exam->description) }}</textarea>
                        </div>

                        <div>
                            <div class="flex items-center justify-between gap-3">
                                <label class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
                                <a href="{{ $baseUrl . route('courses.create', [], false) }}" target="_blank" rel="noopener" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold">Buat mata pelajaran baru</a>
                            </div>
                            @php($pickedCourseId = old('course_id', $exam->course_id))
                            <select name="course_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="" @selected($pickedCourseId === null || $pickedCourseId === '')>(Opsional) Pilih mata pelajaran</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" @selected((string) $pickedCourseId === (string) $course->id)>
                                        {{ $course->title }}{{ $course->grade_level ? ' — '.$course->grade_level : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kelas Peserta Ujian</label>
                            @php($picked = old('grade_level', $exam->grade_level))
                            <select name="grade_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="" @selected($picked === null || $picked === '')>Semua Kelas</option>
                                @foreach($gradeLevels as $level)
                                    <option value="{{ $level }}" @selected($picked === $level)>{{ $level }}</option>
                                @endforeach
                            </select>
                            <div class="text-xs text-gray-500 mt-1">Kosongkan jika ujian untuk semua kelas.</div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">KKM (%)</label>
                                <input type="number" name="passing_score" value="{{ old('passing_score', $exam->passing_score) }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Durasi (menit)</label>
                                <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $exam->duration_minutes) }}" min="1" max="1440" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <label class="mt-2 inline-flex items-center gap-2">
                                    <input type="checkbox" name="is_published" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" @checked(old('is_published', $exam->is_published))>
                                    <span class="text-sm text-gray-700">Published</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Mulai (opsional)</label>
                                <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $exam->starts_at?->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Selesai (opsional)</label>
                                <input type="datetime-local" name="ends_at" value="{{ old('ends_at', $exam->ends_at?->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kode Ujian</label>
                                <input type="text" value="{{ $exam->access_code }}" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Link Siswa</label>
                                <input id="student-link" type="text" value="{{ $studentLink }}" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm" readonly>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <div class="text-xs text-gray-500 mt-1">Bagikan link atau QR code ini ke siswa untuk membuka ujian.</div>
                            </div>
                            <div class="border rounded bg-gray-50 p-4 flex flex-col items-center gap-3">
                                <div class="text-sm font-semibold text-gray-700">QR Code Siswa</div>
                                <div id="exam-qrcode" class="bg-white p-2 border rounded">
                                    @if(!empty($qrBase64))
                                        <img src="data:image/png;base64,{{ $qrBase64 }}" width="180" height="180" alt="QR Code">
                                    @else
                                        <div class="text-xs text-gray-500">QR tidak tersedia</div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" id="copy-student-link" class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 rounded-md text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">Salin Link</button>
                                    <a id="download-exam-qr" href="{{ $baseUrl . route('teacher.exams.qr.download', $exam, false) }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-semibold">Unduh QR</a>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" form="examForm" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Simpan Ujian</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Daftar Soal ({{ $exam->questions->count() }})</h3>
                    @if($exam->questions->isEmpty())
                        <p class="text-gray-500">Belum ada soal.</p>
                    @else
                        <ul class="space-y-4">
                            @foreach($exam->questions as $i => $q)
                                <li class="border p-4 rounded bg-gray-50">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <div class="font-bold">{{ $i + 1 }}. {{ $q->question }}</div>
                                            <div class="text-xs text-gray-500 mt-1">{{ ucfirst(str_replace('_', ' ', $q->type)) }} • {{ $q->points }} poin</div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <a href="{{ $baseUrl . route('teacher.exam-questions.edit', $q, false) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">Edit</a>
                                            <form action="{{ $baseUrl . route('teacher.exam-questions.destroy', $q, false) }}" method="POST" onsubmit="return confirm('Hapus soal ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div id="questionFormSection" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900" x-data="{type: @js(old('type', 'multiple_choice'))}">
                    <h3 class="text-lg font-bold mb-4">Tambah Soal</h3>

                    <form id="questionForm" method="POST" action="{{ $baseUrl . route('teacher.exams.questions.store', $exam, false) }}" class="space-y-4" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tipe Soal</label>
                                <select x-model="type" name="type" form="questionForm" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="multiple_choice">Multiple Choice</option>
                                    <option value="multiple_response">Multiple Response</option>
                                    <option value="true_false">True / False</option>
                                    <option value="short_answer">Short Answer</option>
                                    <option value="numeric">Numeric</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Poin</label>
                                <input type="number" name="points" form="questionForm" value="{{ old('points', 1) }}" min="1" max="1000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Urutan</label>
                                <input type="number" name="order" form="questionForm" value="{{ old('order', 0) }}" min="0" max="100000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lampiran Soal (opsional)</label>
                            <input type="file" name="media_file" form="questionForm" class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*,application/pdf">
                            <div class="mt-1 text-xs text-gray-500">Format: JPG/PNG/GIF/WEBP/PDF • Maks 10MB</div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between gap-3">
                                <label class="block text-sm font-medium text-gray-700">Question Text / Instruction</label>
                                <a href="{{ $baseUrl . route('teacher.latex-guide', [], false) }}" target="_blank" rel="noopener" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold">Contoh LaTeX</a>
                            </div>
                            <textarea name="question" form="questionForm" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Gunakan $...$ atau \\[...\\] untuk rumus LaTeX">{{ old('question') }}</textarea>
                        </div>

                        <div x-show="type === 'multiple_choice' || type === 'multiple_response'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Option A</label>
                                <input type="text" name="option_a" form="questionForm" value="{{ old('option_a') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :required="type === 'multiple_choice' || type === 'multiple_response'">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Option B</label>
                                <input type="text" name="option_b" form="questionForm" value="{{ old('option_b') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :required="type === 'multiple_choice' || type === 'multiple_response'">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Option C</label>
                                <input type="text" name="option_c" form="questionForm" value="{{ old('option_c') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Option D</label>
                                <input type="text" name="option_d" form="questionForm" value="{{ old('option_d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Option E</label>
                                <input type="text" name="option_e" form="questionForm" value="{{ old('option_e') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div x-show="type === 'multiple_choice'">
                            <label class="block text-sm font-medium text-gray-700">Kunci Jawaban</label>
                            @php($pickedCorrect = old('correct_answer_mc', old('correct_answer', 'a')))
                            <select name="correct_answer_mc" form="questionForm" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="a" @selected($pickedCorrect === 'a')>Option A</option>
                                <option value="b" @selected($pickedCorrect === 'b')>Option B</option>
                                <option value="c" @selected($pickedCorrect === 'c')>Option C</option>
                                <option value="d" @selected($pickedCorrect === 'd')>Option D</option>
                                <option value="e" @selected($pickedCorrect === 'e')>Option E</option>
                            </select>
                        </div>

                        <div x-show="type === 'multiple_response'">
                            <label class="block text-sm font-medium text-gray-700">Kunci Jawaban (boleh lebih dari 1)</label>
                            <div class="flex flex-wrap gap-4 mt-2">
                                @foreach(['a','b','c','d','e'] as $k)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="correct_answers[]" form="questionForm" value="{{ $k }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" @checked(is_array(old('correct_answers')) && in_array($k, old('correct_answers'), true))>
                                        <span class="ml-2">Option {{ strtoupper($k) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div x-show="type === 'true_false'">
                            <label class="block text-sm font-medium text-gray-700">Kunci Jawaban</label>
                            @php($pickedTF = old('correct_answer_tf', old('correct_answer', 'true')))
                            <select name="correct_answer_tf" form="questionForm" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="true" @selected($pickedTF === 'true')>True</option>
                                <option value="false" @selected($pickedTF === 'false')>False</option>
                            </select>
                        </div>

                        <div x-show="type === 'short_answer' || type === 'numeric'">
                            <label class="block text-sm font-medium text-gray-700">Kunci Jawaban</label>
                            <input type="text" name="correct_answer_text" form="questionForm" value="{{ old('correct_answer_text') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" form="questionForm" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Simpan Soal</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <a href="{{ $baseUrl . route('teacher.exams.index', [], false) }}" class="text-gray-500 hover:text-gray-700">&larr; Kembali</a>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    (function () {
        const linkInput = document.getElementById('student-link');
        const copyBtn = document.getElementById('copy-student-link');
        if (copyBtn && linkInput) {
            copyBtn.addEventListener('click', async function () {
                try {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        await navigator.clipboard.writeText(linkInput.value);
                    } else {
                        linkInput.focus();
                        linkInput.select();
                        document.execCommand('copy');
                        linkInput.setSelectionRange(0, 0);
                        linkInput.blur();
                    }
                    copyBtn.textContent = 'Tersalin';
                    setTimeout(function () {
                        copyBtn.textContent = 'Salin Link';
                    }, 1200);
                } catch (e) {
                }
            });
        }
    })();
</script>
