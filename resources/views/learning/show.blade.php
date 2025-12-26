<x-app-layout>
    <div class="flex h-[calc(100vh-65px)] bg-gray-100 overflow-hidden">
        <!-- Sidebar -->
        <div class="w-80 bg-white border-r border-gray-200 flex flex-col h-full overflow-hidden shrink-0">
            <div class="p-4 border-b border-gray-200">
                <h2 class="font-bold text-lg text-gray-800 truncate" title="{{ $course->title }}">
                    {{ $course->title }}
                </h2>
                <!-- Simple Progress Bar -->
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
                @php
                    $previousModuleCompleted = true;
                @endphp
                @foreach($course->modules as $mod)
                    @php
                        // Check if THIS module is completed (all lessons completed)
                        // Note: optimize this in controller if performance is an issue
                        $isModuleCompleted = $mod->lessons->every(fn($l) => $l->usersCompleted->contains(auth()->id()));
                        
                        // Current module is locked if previous module is NOT completed
                        // AND it's not the first module (handled by $previousModuleCompleted init to true)
                        $isModuleLocked = !$previousModuleCompleted;
                    @endphp

                    <div x-data="{ open: {{ $mod->id === $module->id ? 'true' : 'false' }} }">
                        <button @click="if(!{{ $isModuleLocked ? 'true' : 'false' }}) open = !open" class="flex items-center justify-between w-full p-4 bg-gray-50 hover:bg-gray-100 transition border-b border-gray-100 {{ $isModuleLocked ? 'opacity-75 cursor-not-allowed' : '' }}">
                            <div class="flex items-center">
                                @if($isModuleLocked)
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                @endif
                                <span class="font-medium text-sm text-gray-700">{{ $mod->title }}</span>
                            </div>
                            @if(!$isModuleLocked)
                                <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            @endif
                        </button>
                        
                        <div x-show="open && !{{ $isModuleLocked ? 'true' : 'false' }}" class="bg-white">
                            @foreach($mod->lessons as $l)
                                @php
                                    $isCurrent = $l->id === $lesson->id;
                                    $isLessonCompleted = $l->usersCompleted->contains(auth()->id());
                                @endphp
                                <a href="{{ route('learning.lesson', [$course, $mod, $l]) }}" class="flex items-center p-3 pl-8 hover:bg-indigo-50 {{ $isCurrent ? 'bg-indigo-50 border-r-4 border-indigo-500' : '' }}">
                                    @if($isLessonCompleted)
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 mr-2"></div>
                                    @endif
                                    <span class="text-sm text-gray-600 truncate">{{ $l->title }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    @php
                        $previousModuleCompleted = $isModuleCompleted;
                    @endphp
                @endforeach
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-full overflow-hidden relative w-full">
            <header class="bg-white shadow-sm z-10 p-4 flex justify-between items-center shrink-0">
                <h1 class="text-xl font-semibold text-gray-800 truncate">{{ $lesson->title }}</h1>
                <form action="{{ route('learning.complete', [$course, $module, $lesson]) }}" method="POST">
                    @csrf
                    <button id="complete-btn" type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition shadow-sm font-medium text-sm disabled:opacity-50 disabled:cursor-not-allowed" {{ $lesson->type === 'video' ? 'disabled' : '' }}>
                        {{ $isCompleted ? 'Pelajaran Berikutnya' : 'Tandai Selesai & Lanjut' }}
                    </button>
                </form>
                <a href="{{ route('dashboard') }}" class="ml-3 px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition shadow-sm font-medium text-sm">
                    Stop Belajar
                </a>
            </header>
            
            <main class="flex-1 overflow-y-auto p-4 md:p-8">
                <div class="max-w-5xl mx-auto">
                    <!-- Error Message -->
                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="bg-white p-6 md:p-8 rounded-lg shadow-sm">
                        @if($lesson->type === 'video')
                            @php
                                $iframeSrc = null;
                                if (Str::contains($lesson->content, 'iframe')) {
                                    $match = [];
                                    if (preg_match('/src=["\']([^"\']+)["\']/', $lesson->content, $match)) {
                                        $iframeSrc = $match[1] ?? null;
                                    }
                                }
                                $content = trim($lesson->content ?? '');
                                $videoId = null;
                                if (!$iframeSrc && !empty($content)) {
                                    if (Str::startsWith($content, ['https://youtu.be/', 'http://youtu.be/'])) {
                                        $videoId = trim(Str::after($content, 'youtu.be/'));
                                    } elseif (Str::contains($content, 'youtube.com/watch')) {
                                        $query = parse_url($content, PHP_URL_QUERY);
                                        parse_str($query ?? '', $params);
                                        $videoId = $params['v'] ?? null;
                                    } elseif (Str::contains($content, 'youtube.com/embed/')) {
                                        $videoId = trim(Str::after($content, 'embed/'));
                                    }
                                }
                                $embedUrl = $iframeSrc ?: ($videoId ? ('https://www.youtube.com/embed/' . $videoId) : null);
                                $isYoutube = $embedUrl && (Str::contains($embedUrl, 'youtube.com') || Str::contains($embedUrl, 'youtube-nocookie.com'));
                                if ($isYoutube) {
                                    $separator = Str::contains($embedUrl, '?') ? '&' : '?';
                                    $origin = request()->getSchemeAndHttpHost();
                                    $embedUrl = $embedUrl . $separator . 'enablejsapi=1&origin=' . urlencode($origin);
                                }
                            @endphp
                            <div class="relative mb-6 bg-black rounded-lg overflow-hidden" style="padding-top:56.25%;">
                                @if($embedUrl)
                                    <iframe id="lesson-player" class="absolute inset-0 w-full h-full" src="{{ $embedUrl }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                @elseif($lesson->file_path)
                                    <video id="lesson-video" controls class="absolute inset-0 w-full h-full object-contain">
                                        <source src="{{ Storage::url($lesson->file_path) }}" type="video/mp4">
                                        Browser Anda tidak mendukung tag video.
                                    </video>
                                @endif
                            </div>
                        @elseif($lesson->type === 'pdf')
                            <div class="h-[800px] mb-6">
                                <iframe src="{{ Storage::url($lesson->file_path) }}" class="w-full h-full rounded-lg border border-gray-200"></iframe>
                            </div>
                        @elseif(in_array($lesson->type, ['doc','xls','ppt']))
                            @php
                                $fileUrl = $lesson->file_path ? Storage::url($lesson->file_path) : null;
                                $isLocal = in_array(request()->getHost(), ['localhost', '127.0.0.1', '::1']);
                                $viewer = $fileUrl ? 'https://view.officeapps.live.com/op/view.aspx?src=' . urlencode(url($fileUrl)) : null;
                            @endphp
                            
                            @if($isLocal)
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                Pratinjau dokumen tidak tersedia di localhost karena Microsoft Office Online Viewer memerlukan URL publik.
                                            </p>
                                            <p class="text-sm text-yellow-700 mt-1">
                                                Silakan unduh file di bagian bawah halaman untuk melihat isinya.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @elseif($viewer)
                                <div class="h-[800px] mb-6">
                                    <iframe src="{{ $viewer }}" class="w-full h-full rounded-lg border border-gray-200"></iframe>
                                </div>
                            @endif
                        @endif
                        
                        @if($lesson->type !== 'video')
                            <div class="prose max-w-none mt-6">
                                {!! $lesson->content !!}
                            </div>
                        @endif

                        @if($lesson->basic_competency || $lesson->learning_objectives)
                            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($lesson->basic_competency)
                                <div class="rounded-xl border border-gray-200 bg-background p-5">
                                    <div class="flex items-center gap-2 mb-2">
                                        <x-heroicon name="academic-cap" class="h-5 w-5 text-tertiary" />
                                        <h3 class="text-base font-semibold text-text-dark">Kompetensi Dasar (KD)</h3>
                                    </div>
                                    <div class="text-sm text-gray-700 whitespace-pre-line">{{ $lesson->basic_competency }}</div>
                                </div>
                                @endif
                                @if($lesson->learning_objectives)
                                <div class="rounded-xl border border-gray-200 bg-background p-5">
                                    <div class="flex items-center gap-2 mb-2">
                                        <x-heroicon name="rectangle-stack" class="h-5 w-5 text-secondary" />
                                        <h3 class="text-base font-semibold text-text-dark">Tujuan Pembelajaran</h3>
                                    </div>
                                    <div class="text-sm text-gray-700 whitespace-pre-line">{{ $lesson->learning_objectives }}</div>
                                </div>
                                @endif
                            </div>
                        @endif
                        
                        @if($lesson->file_path && $lesson->type !== 'video' && $lesson->type !== 'pdf')
                            <div class="mt-8 pt-6 border-t border-gray-100">
                                <h3 class="text-lg font-medium text-gray-900 mb-3">Unduhan</h3>
                                <a href="{{ Storage::url($lesson->file_path) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <svg class="w-5 h-5 mr-2 -ml-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Unduh Materi
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
    @if($lesson->type === 'video')
        @php
            $isYoutube = isset($embedUrl) && Str::contains($embedUrl, 'youtube.com');
        @endphp
        <script>
            (function () {
                var btn = document.getElementById('complete-btn');
                function enableBtn() { if (btn) btn.disabled = false; }
                @if($isYoutube)
                var s = document.createElement('script');
                s.src = 'https://www.youtube.com/iframe_api';
                document.head.appendChild(s);
                window.onYouTubeIframeAPIReady = function () {
                    new YT.Player('lesson-player', {
                        events: {
                            'onStateChange': function (e) {
                                if (e.data === YT.PlayerState.ENDED) enableBtn();
                            }
                        }
                    });
                };
                @else
                var v = document.getElementById('lesson-video');
                if (v) v.addEventListener('ended', enableBtn);
                @endif
            })();
        </script>
    @endif
</x-app-layout>
