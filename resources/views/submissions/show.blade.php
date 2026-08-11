<x-app-layout>
    <div x-data="{ sidebarOpen: false }" class="flex h-[calc(100vh-65px)] bg-gray-100 overflow-hidden relative">
        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/50 z-20 md:hidden" @click="sidebarOpen = false" style="display: none;"></div>

        <!-- Sidebar (same as learning/show.blade.php) -->
        <div :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" class="absolute z-30 md:relative md:translate-x-0 transition-transform duration-300 w-80 bg-white border-r border-gray-200 flex flex-col h-full overflow-hidden shrink-0">
            <div class="p-4 border-b border-gray-200">
                <h2 class="font-bold text-lg text-gray-800 truncate" title="{{ $course->title }}">{{ $course->title }}</h2>
                @php
                    $totalLessons = $course->modules->sum(fn($m) => $m->lessons->count());
                    $completedLessons = auth()->user()->completedLessons()->whereIn('lesson_id', $course->modules->pluck('lessons')->flatten()->pluck('id'))->count();
                    $progress = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
                @endphp
                <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ round($progress) }}% Selesai</p>
            </div>

            <div class="flex-1 overflow-y-auto">
                @foreach($course->modules as $mod)
                    @php
                        $isModuleCompleted = $mod->lessons->every(fn($l) => $l->usersCompleted->contains(auth()->id()));
                        $isModuleLocked = false;
                        // Simplified: just show all modules
                    @endphp

                    <div x-data="{ open: {{ $mod->id === $module->id ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="flex items-center justify-between w-full p-4 bg-gray-50 hover:bg-gray-100 transition border-b border-gray-100">
                            <span class="font-medium text-sm text-gray-700">{{ $mod->title }}</span>
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="open" class="bg-white">
                            @foreach($mod->lessons as $l)
                                @php
                                    $isCurrent = $l->id === $lesson->id;
                                    $isLessonCompleted = $l->usersCompleted->contains(auth()->id());
                                @endphp
                                <a href="{{ route('learning.lesson', [$course, $mod, $l]) }}" class="flex items-center p-3 pl-8 hover:bg-indigo-50 {{ $isCurrent ? 'bg-indigo-50 border-r-4 border-indigo-500' : '' }}">
                                    @if($isLessonCompleted)
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 mr-2"></div>
                                    @endif
                                    <span class="text-sm text-gray-600 truncate">{{ $l->title }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-full overflow-hidden relative w-full">
            <header class="bg-white shadow-sm z-10 p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center shrink-0 gap-4">
                <div class="flex items-center gap-3 w-full sm:w-auto overflow-hidden">
                    <button @click="sidebarOpen = true" class="md:hidden p-2 -ml-2 rounded-md hover:bg-gray-100 text-gray-600 focus:outline-none shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800 truncate" title="{{ $assignment->title }}">{{ $assignment->title }}</h1>
                </div>
                <a href="{{ route('learning.lesson', [$course, $module, $lesson]) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition shadow-sm font-medium text-sm whitespace-nowrap text-center">
                    Kembali
                </a>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 pb-24 md:pb-8">
                <div class="max-w-4xl mx-auto">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Assignment Details -->
                    <div class="bg-white p-6 md:p-8 rounded-lg shadow-sm mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">{{ $assignment->title }}</h2>

                        @if($assignment->description)
                            <div class="prose max-w-none text-gray-700">
                                <p class="whitespace-pre-line">{{ $assignment->description }}</p>
                            </div>
                        @endif

                        <div class="mt-4 flex flex-wrap gap-4 text-sm text-gray-500">
                            @if($assignment->due_date)
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span>Tenggat: {{ $assignment->due_date->format('d M Y, H:i') }}</span>
                                    @if($assignment->due_date->isPast())
                                        <span class="text-red-600 font-medium">(Lewat tenggat)</span>
                                    @endif
                                </div>
                            @endif
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                <span>Maks. {{ $assignment->file_size_limit }} MB</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submission Form / Status -->
                    <div class="bg-white p-6 md:p-8 rounded-lg shadow-sm">
                        @if($submission)
                            <!-- Submission Status -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Status Pengumpulan</h3>

                                <div class="flex flex-wrap gap-3 mb-4">
                                    <span class="px-3 py-1 text-sm font-medium rounded-full {{ $submission->status === 'graded' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $submission->status === 'graded' ? 'S Dinilai' : 'Menunggu Penilaian' }}
                                    </span>

                                    @if($submission->status === 'graded' && $submission->score !== null)
                                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-indigo-100 text-indigo-800">
                                            Nilai: {{ $submission->score }} / 100
                                        </span>
                                    @endif

                                    <span class="px-3 py-1 text-sm font-medium rounded-full bg-gray-100 text-gray-600">
                                        Dikumpulkan: {{ $submission->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>

                                @if($submission->status === 'graded' && $submission->feedback)
                                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                        <h4 class="text-sm font-semibold text-blue-900 mb-1">Feedback dari Guru:</h4>
                                        <p class="text-sm text-blue-800 whitespace-pre-line">{{ $submission->feedback }}</p>
                                    </div>
                                @endif

                                @if($submission->file_path)
                                    <div class="mt-4">
                                        <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            <svg class="w-5 h-5 mr-2 -ml-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            {{ __('Lihat File Tugas') }}
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Re-submit -->
                            @if($submission->status !== 'graded' || auth()->user()->hasRole(['admin', 'teacher']))
                                <form action="{{ route('learning.assignments.submit', [$course, $module, $lesson, $assignment]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Perbarui Pengumpulan</label>
                                        <input type="file" name="file" class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-xl file:border-0 file:bg-indigo-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip,.rar">

                                        <div class="mt-3">
                                            <label class="block text-sm font-medium text-gray-600">Catatan (Opsional)</label>
                                            <textarea name="notes" rows="2" class="mt-1 block w-full rounded-xl border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('notes', $submission->notes) }}</textarea>
                                        </div>

                                        <button type="submit" class="mt-3 inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:opacity-95">
                                            Kirim Ulang Tugas
                                        </button>
                                    </div>
                                </form>
                            @endif
                        @else
                            <!-- Not Submitted Yet -->
                            <form action="{{ route('learning.assignments.submit', [$course, $module, $lesson, $assignment]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload File Tugas</label>
                                    <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        <p class="mt-2 text-sm text-gray-600">PDF, Word, Excel, PPT, gambar, atau zip</p>
                                        <p class="text-xs text-gray-500">Maks. {{ $assignment->file_size_limit }} MB</p>
                                        <input type="file" name="file" class="mt-3 block w-full text-sm text-gray-700 file:mr-4 file:rounded-xl file:border-0 file:bg-indigo-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip,.rar" required>
                                    </div>

                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-600">Catatan (Opsional)</label>
                                        <textarea name="notes" rows="2" placeholder="Tambahkan catatan untuk guru..." class="mt-1 block w-full rounded-xl border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                                    </div>

                                    <button type="submit" class="mt-4 inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:opacity-95">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Kirim Tugas
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>
