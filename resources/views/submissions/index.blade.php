<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="text-xl font-semibold text-text-dark leading-tight">
                {{ __('Submission Tugas') }}
            </h2>
            <div class="text-sm text-gray-600">
                {{ __('Tugas: ') . $assignment->title }}
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-background">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-6 text-sm text-gray-600">
                <a href="{{ route('courses.modules.index', $module->course) }}" class="hover:text-gray-900">Course</a>
                <span class="mx-2">/</span>
                <a href="{{ route('modules.lessons.index', $module) }}" class="hover:text-gray-900">{{ $module->title }}</a>
                <span class="mx-2">/</span>
                <a href="{{ route('learning.lesson', [$module->course, $module, $lesson]) }}" class="hover:text-gray-900">{{ $lesson->title }}</a>
                <span class="mx-2">/</span>
                <a href="{{ route('modules.lessons.assignments.index', [$module, $lesson]) }}" class="hover:text-gray-900">Tugas</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900">Submission</span>
            </nav>

            <!-- Assignment Info -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $assignment->title }}</h3>
                        @if($assignment->description)
                            <p class="mt-1 text-sm text-gray-600">{{ Str::limit($assignment->description, 150) }}</p>
                        @endif
                    </div>
                    <a href="{{ route('modules.lessons.assignments.index', [$module, $lesson]) }}" class="text-sm text-gray-600 hover:text-gray-900">Kembali</a>
                </div>
                <div class="mt-3 flex flex-wrap gap-4 text-sm text-gray-500">
                    @if($assignment->due_date)
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Tenggat: {{ $assignment->due_date->format('d M Y, H:i') }}
                        </span>
                    @endif
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        {{ $students->count() }} siswa enrolled
                    </span>
                </div>
            </div>

            <!-- Submissions Table -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h4 class="text-sm font-semibold text-gray-900">Daftar Submission ({{ $submissions->count() }})</h4>
                </div>

                @if($students->isEmpty())
                    <div class="p-8 text-center text-gray-500">Belum ada siswa yang terdaftar di course ini.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dikumpulkan</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($students as $student)
                                    @php
                                        $submission = $submissions->get($student->id);
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($submission)
                                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $submission->status === 'graded' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ $submission->status === 'graded' ? 'Dinilai' : 'Pending' }}
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-500">Belum submit</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($submission && $submission->status === 'graded' && $submission->score !== null)
                                                <span class="text-sm font-semibold text-indigo-600">{{ $submission->score }}</span>
                                            @elseif($submission)
                                                <span class="text-sm text-gray-400">-</span>
                                            @else
                                                <span class="text-sm text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            @if($submission)
                                                {{ $submission->created_at->format('d M Y, H:i') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm font-medium">
                                            @if($submission)
                                                <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 mr-3">Lihat File</a>
                                                <button onclick="openGradeModal({{ $submission->id }}, '{{ $student->name }}', {{ $submission->score ?? 'null' }}, '{{ $submission->feedback ?? '' }}')" class="text-purple-600 hover:text-purple-900">
                                                    {{ $submission->status === 'graded' ? 'Edit Nilai' : 'Beri Nilai' }}
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Grade Modal -->
    <div id="gradeModal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeGradeModal()"></div>
        <div class="relative mx-auto flex min-h-full max-w-lg items-center justify-center p-4">
            <div class="w-full rounded-2xl bg-white shadow-xl ring-1 ring-black/5">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900">Beri Nilai - <span id="gradeStudentName"></span></h3>

                    <form id="gradeForm" action="" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mt-4">
                            <label class="block text-sm font-semibold text-gray-700">Nilai (0-100)</label>
                            <input type="number" id="gradeScore" name="score" min="0" max="100" step="0.01" class="mt-2 block w-full rounded-xl border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-semibold text-gray-700">Feedback</label>
                            <textarea id="gradeFeedback" name="feedback" rows="4" class="mt-2 block w-full rounded-xl border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                            <button type="button" onclick="closeGradeModal()" class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">Batal</button>
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:opacity-95">Simpan Nilai</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openGradeModal(submissionId, studentName, score, feedback) {
            document.getElementById('gradeModal').classList.remove('hidden');
            document.getElementById('gradeModal').setAttribute('aria-hidden', 'false');
            document.getElementById('gradeStudentName').textContent = studentName;
            document.getElementById('gradeScore').value = score ?? '';
            document.getElementById('gradeFeedback').value = feedback ?? '';
            // Update form action
            document.getElementById('gradeForm').action = '{{ route('modules.lessons.assignments.submissions.index', [$module, $lesson, $assignment]) }}/' + submissionId + '/grade';
            // Actually, let's use the grade route
            document.getElementById('gradeForm').action = '/teacher/submissions/' + submissionId + '/grade';
        }

        function closeGradeModal() {
            document.getElementById('gradeModal').classList.add('hidden');
            document.getElementById('gradeModal').setAttribute('aria-hidden', 'true');
        }
    </script>
</x-app-layout>
