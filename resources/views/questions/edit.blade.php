<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Soal
                </h2>
                <div class="text-sm text-gray-500">
                    {{ $question->quiz->title }}
                </div>
            </div>

            <a href="{{ route('quizzes.edit', $question->quiz) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
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

                    <div x-data="{
                    type: @js(old('type', $question->type ?? 'multiple_choice')),
                    pairs: @js(old('pairs', $pairs)),
                    sequence_items: @js(old('sequence', $sequenceItems)),
                    addPair() { this.pairs.push({left: '', right: ''}); },
                    removePair(index) { this.pairs.splice(index, 1); },
                    addSequenceItem() { this.sequence_items.push(''); },
                    removeSequenceItem(index) { this.sequence_items.splice(index, 1); }
                }">
                    <form method="POST" action="{{ route('questions.update', $question) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Tipe Soal</label>
                            <select x-model="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="multiple_choice">Pilihan Ganda</option>
                                <option value="multiple_response">Jawaban Majemuk</option>
                                <option value="true_false">Benar / Salah</option>
                                <option value="short_answer">Isian Singkat</option>
                                <option value="numeric">Angka</option>
                                <option value="essay">Esai</option>
                                <option value="matching">Menjodohkan</option>
                                <option value="sequencing">Mengurutkan</option>
                                <option value="drag_drop">Drag & Drop</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Lampiran (Opsional)</label>

                            @if($question->media_url)
                                @php
                                    $isRemote = \Illuminate\Support\Str::startsWith($question->media_url, ['http://', 'https://', '/']);
                                    $url = $isRemote ? $question->media_url : \Illuminate\Support\Facades\Storage::disk('public')->url($question->media_url);
                                    $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
                                    $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp'], true);
                                @endphp

                                <div class="mt-2 flex flex-col gap-2">
                                    @if($isImage)
                                        <img src="{{ $url }}" alt="Lampiran soal" class="max-w-md h-auto rounded border shadow-sm">
                                    @endif
                                    <a href="{{ $url }}" target="_blank" rel="noopener" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold">Buka lampiran saat ini</a>
                                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                        <input type="checkbox" name="remove_media" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        Hapus lampiran
                                    </label>
                                </div>
                            @endif

                            <input type="file" name="media_file" class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*,application/pdf">
                            <div class="mt-1 text-xs text-gray-500">Upload file baru akan mengganti lampiran lama • Maks 10MB</div>
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center justify-between gap-3">
                                <label class="block text-sm font-medium text-gray-700">Teks Soal</label>
                                <a href="{{ route('teacher.latex-guide') }}" target="_blank" rel="noopener" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold">Contoh LaTeX</a>
                            </div>
                            <div class="flex flex-wrap gap-1 bg-gray-100 p-1.5 rounded-t-md border border-gray-300 border-b-0 mt-1">
                                <button type="button" onclick="insertFormatTag('single-question-textarea', '[b]', '[/b]')" class="px-2.5 py-1 text-xs font-bold bg-white rounded border border-gray-200 hover:bg-gray-50 focus:outline-none" title="Tebal (Bold)">B</button>
                                <button type="button" onclick="insertFormatTag('single-question-textarea', '[i]', '[/i]')" class="px-2.5 py-1 text-xs italic bg-white rounded border border-gray-200 hover:bg-gray-50 focus:outline-none" title="Miring (Italic)">I</button>
                                <button type="button" onclick="insertFormatTag('single-question-textarea', '[u]', '[/u]')" class="px-2.5 py-1 text-xs underline bg-white rounded border border-gray-200 hover:bg-gray-50 focus:outline-none" title="Garis Bawah (Underline)">U</button>
                                <div class="w-[1px] bg-gray-300 mx-1 self-stretch"></div>
                                <button type="button" onclick="insertFormatTag('single-question-textarea', '[left]', '[/left]')" class="px-2 py-1 text-xs bg-white rounded border border-gray-200 hover:bg-gray-50 focus:outline-none" title="Rata Kiri">⫷ Kiri</button>
                                <button type="button" onclick="insertFormatTag('single-question-textarea', '[center]', '[/center]')" class="px-2 py-1 text-xs bg-white rounded border border-gray-200 hover:bg-gray-50 focus:outline-none" title="Rata Tengah">〓 Tengah</button>
                                <button type="button" onclick="insertFormatTag('single-question-textarea', '[right]', '[/right]')" class="px-2 py-1 text-xs bg-white rounded border border-gray-200 hover:bg-gray-50 focus:outline-none" title="Rata Kanan">Kanan ⫸</button>
                            </div>
                            <textarea id="single-question-textarea" name="question" rows="3" class="block w-full rounded-b-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('question', $question->question) }}</textarea>
                            <div class="mt-2 p-3 rounded border bg-gray-50">
                                <div class="text-xs text-gray-500 mb-2">Preview</div>
                                <div id="question-preview" class="prose whitespace-pre-wrap"></div>
                            </div>
                        </div>

                        <div x-show="type === 'multiple_choice' || type === 'multiple_response'" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Opsi A</label>
                                <input type="text" name="option_a" value="{{ old('option_a', $options['a'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Opsi B</label>
                                <input type="text" name="option_b" value="{{ old('option_b', $options['b'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Opsi C</label>
                                <input type="text" name="option_c" value="{{ old('option_c', $options['c'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Opsi D</label>
                                <input type="text" name="option_d" value="{{ old('option_d', $options['d'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Opsi E</label>
                                <input type="text" name="option_e" value="{{ old('option_e', $options['e'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="mb-4" x-show="type === 'multiple_choice'">
                            <label class="block text-sm font-medium text-gray-700">Kunci Jawaban</label>
                            <select name="correct_answer" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :disabled="type !== 'multiple_choice'">
                                @php $picked = old('correct_answer', $question->correct_answer); @endphp
                                <option value="a" @selected($picked==='a')>Opsi A</option>
                                <option value="b" @selected($picked==='b')>Opsi B</option>
                                <option value="c" @selected($picked==='c')>Opsi C</option>
                                <option value="d" @selected($picked==='d')>Opsi D</option>
                                <option value="e" @selected($picked==='e')>Opsi E</option>
                            </select>
                        </div>

                        <div class="mb-4" x-show="type === 'multiple_response'">
                            <label class="block text-sm font-medium text-gray-700">Kunci Jawaban (Boleh Lebih dari 1)</label>
                            @php $picked = old('correct_answers', $correctAnswers); @endphp
                            <div class="flex flex-wrap gap-4 mt-2">
                                @foreach(['a','b','c','d','e'] as $k)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="correct_answers[]" value="{{ $k }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" @checked(is_array($picked) && in_array($k, $picked, true))>
                                        <span class="ml-2">Opsi {{ strtoupper($k) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4" x-show="type === 'true_false'">
                            <label class="block text-sm font-medium text-gray-700">Kunci Jawaban</label>
                            @php $picked = old('correct_answer', $question->correct_answer); @endphp
                            <select name="correct_answer" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :disabled="type !== 'true_false'">
                                <option value="true" @selected($picked==='true')>Benar</option>
                                <option value="false" @selected($picked==='false')>Salah</option>
                            </select>
                        </div>

                        <div class="mb-4" x-show="type === 'short_answer' || type === 'numeric'">
                            <label class="block text-sm font-medium text-gray-700">Kunci Jawaban</label>
                            <input type="text" name="correct_answer_text" value="{{ old('correct_answer_text', in_array($question->type, ['short_answer', 'numeric'], true) ? $question->correct_answer : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :disabled="!(type === 'short_answer' || type === 'numeric')">
                        </div>

                        <div class="mb-4" x-show="type === 'essay'">
                            <div class="text-sm text-gray-600">Esai dinilai manual. Tidak ada kunci jawaban.</div>
                        </div>

                        <div class="mb-4" x-show="type === 'matching' || type === 'drag_drop'">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pasangan (Item - Pasangan)</label>
                            <template x-for="(pair, index) in pairs" :key="index">
                                <div class="flex gap-2 mb-2">
                                    <input type="text" :name="'pairs['+index+'][left]'" x-model="pair.left" placeholder="Item" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="self-center">→</span>
                                    <input type="text" :name="'pairs['+index+'][right]'" x-model="pair.right" placeholder="Pasangan" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <button type="button" @click="removePair(index)" class="text-red-500 hover:text-red-700" x-show="pairs.length > 2">×</button>
                                </div>
                            </template>
                            <button type="button" @click="addPair()" class="text-sm text-blue-600 hover:text-blue-800">+ Tambah Pasangan</button>
                        </div>

                        <div class="mb-4" x-show="type === 'sequencing'">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Urutan Benar</label>
                            <template x-for="(item, index) in sequence_items" :key="index">
                                <div class="flex gap-2 mb-2">
                                    <input type="text" :name="'sequence['+index+']'" x-model="sequence_items[index]" placeholder="Item" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <button type="button" @click="removeSequenceItem(index)" class="text-red-500 hover:text-red-700" x-show="sequence_items.length > 2">×</button>
                                </div>
                            </template>
                            <button type="button" @click="addSequenceItem()" class="text-sm text-blue-600 hover:text-blue-800">+ Tambah Item</button>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('quizzes.edit', $question->quiz) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function insertFormatTag(textareaId, startTag, endTag) {
            const textarea = document.getElementById(textareaId);
            if (!textarea) return;
            
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;
            const selected = text.substring(start, end);
            const replacement = startTag + selected + endTag;
            
            textarea.value = text.substring(0, start) + replacement + text.substring(end);
            textarea.focus();
            textarea.selectionStart = start + startTag.length;
            textarea.selectionEnd = start + startTag.length + selected.length;
            textarea.dispatchEvent(new Event('input'));
        }

        function parseBBCode(text) {
            if (!text) return '';
            let escaped = text
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
            
            escaped = escaped.replace(/(https?:\/\/[^\s\<>\"]+)/g, '<a href="$1" target="_blank" class="text-blue-600 hover:underline font-semibold">$1</a>');

            return escaped
                .replace(/\[b\](.*?)\[\/b\]/gi, '<strong>$1</strong>')
                .replace(/\[i\](.*?)\[\/i\]/gi, '<em>$1</em>')
                .replace(/\[u\](.*?)\[\/u\]/gi, '<u>$1</u>')
                .replace(/\[left\](.*?)\[\/left\]/gi, '<div style="text-align: left;">$1</div>')
                .replace(/\[center\](.*?)\[\/center\]/gi, '<div style="text-align: center;">$1</div>')
                .replace(/\[right\](.*?)\[\/right\]/gi, '<div style="text-align: right;">$1</div>');
        }

        (function(){
            const ta = document.getElementById('single-question-textarea');
            const pv = document.getElementById('question-preview');
            const update = () => {
                if (!pv || !ta) return;
                pv.innerHTML = parseBBCode(ta.value || '');
            };
            if (ta) {
                ta.addEventListener('input', update);
                update();
            }
        })();
    </script>
</x-app-layout>
