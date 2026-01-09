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
                    <form method="POST" action="{{ route('questions.update', $question) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Tipe Soal</label>
                            <select x-model="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="multiple_choice">Multiple Choice</option>
                                <option value="multiple_response">Multiple Response</option>
                                <option value="true_false">True / False</option>
                                <option value="short_answer">Short Answer</option>
                                <option value="numeric">Numeric</option>
                                <option value="essay">Essay</option>
                                <option value="matching">Matching</option>
                                <option value="sequencing">Sequencing</option>
                                <option value="drag_drop">Drag & Drop</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Media URL (Opsional)</label>
                            <input type="text" name="media_url" value="{{ old('media_url', $question->media_url) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="https://example.com/image.jpg">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Teks Soal</label>
                            <textarea name="question" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('question', $question->question) }}</textarea>
                        </div>

                        <div x-show="type === 'multiple_choice' || type === 'multiple_response'" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Option A</label>
                                <input type="text" name="option_a" value="{{ old('option_a', $options['a'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Option B</label>
                                <input type="text" name="option_b" value="{{ old('option_b', $options['b'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Option C</label>
                                <input type="text" name="option_c" value="{{ old('option_c', $options['c'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Option D</label>
                                <input type="text" name="option_d" value="{{ old('option_d', $options['d'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="mb-4" x-show="type === 'multiple_choice'">
                            <label class="block text-sm font-medium text-gray-700">Kunci Jawaban</label>
                            <select name="correct_answer" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :disabled="type !== 'multiple_choice'">
                                @php $picked = old('correct_answer', $question->correct_answer); @endphp
                                <option value="a" @selected($picked==='a')>Option A</option>
                                <option value="b" @selected($picked==='b')>Option B</option>
                                <option value="c" @selected($picked==='c')>Option C</option>
                                <option value="d" @selected($picked==='d')>Option D</option>
                            </select>
                        </div>

                        <div class="mb-4" x-show="type === 'multiple_response'">
                            <label class="block text-sm font-medium text-gray-700">Kunci Jawaban (Boleh Lebih dari 1)</label>
                            @php $picked = old('correct_answers', $correctAnswers); @endphp
                            <div class="flex flex-wrap gap-4 mt-2">
                                @foreach(['a','b','c','d'] as $k)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="correct_answers[]" value="{{ $k }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" @checked(is_array($picked) && in_array($k, $picked, true))>
                                        <span class="ml-2">Option {{ strtoupper($k) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4" x-show="type === 'true_false'">
                            <label class="block text-sm font-medium text-gray-700">Kunci Jawaban</label>
                            @php $picked = old('correct_answer', $question->correct_answer); @endphp
                            <select name="correct_answer" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :disabled="type !== 'true_false'">
                                <option value="true" @selected($picked==='true')>True</option>
                                <option value="false" @selected($picked==='false')>False</option>
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pairs (Item - Match)</label>
                            <template x-for="(pair, index) in pairs" :key="index">
                                <div class="flex gap-2 mb-2">
                                    <input type="text" :name="'pairs['+index+'][left]'" x-model="pair.left" placeholder="Item" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="self-center">→</span>
                                    <input type="text" :name="'pairs['+index+'][right]'" x-model="pair.right" placeholder="Match" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <button type="button" @click="removePair(index)" class="text-red-500 hover:text-red-700" x-show="pairs.length > 2">×</button>
                                </div>
                            </template>
                            <button type="button" @click="addPair()" class="text-sm text-blue-600 hover:text-blue-800">+ Tambah Pair</button>
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
</x-app-layout>
