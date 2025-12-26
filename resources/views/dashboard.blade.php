<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-xl">
            <div class="relative bg-gradient-to-r from-tertiary to-secondary h-28 w-full flex items-center px-6">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="relative flex items-center justify-between w-full">
                    <div class="text-text-light drop-shadow-md">
                        <div class="text-sm opacity-90">Selamat datang</div>
                        <div class="text-2xl font-bold capitalize">{{ Auth::user()->name }}</div>
                    </div>
                    <div class="flex items-center gap-3">
                        @php
                            $avatar = Auth::user()->avatar ?? null;
                            $avatarUrl = $avatar ? asset('storage/'.$avatar) : null;
                        @endphp
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="Foto Guru" class="relative h-20 w-20 rounded-full object-cover ring-4 ring-white/70 shadow-lg" />
                        @else
                            <span class="relative h-20 w-20 rounded-full bg-white/20 flex items-center justify-center text-text-light ring-4 ring-white/70 shadow-lg">
                                <x-heroicon name="user" class="h-10 w-10" />
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="relative rounded-xl overflow-hidden p-4 text-text-light shadow hover:shadow-lg transition bg-gradient-to-br from-primary to-primary">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <div class="relative flex items-center gap-4">
                            <div class="h-12 w-12 rounded-full bg-white/15 flex items-center justify-center">
                                <x-heroicon name="user" class="h-7 w-7" />
                            </div>
                            <div>
                                <div class="text-xs uppercase">Peran</div>
                                <div class="text-lg font-bold capitalize">{{ Auth::user()->getRoleNames()->first() }}</div>
                            </div>
                        </div>
                        <x-heroicon name="user" class="absolute right-2 top-2 h-20 w-20 opacity-10 text-text-light" />
                    </div>
                    <a href="{{ route('courses.index') }}" class="relative rounded-xl overflow-hidden p-4 text-text-light shadow hover:shadow-lg transition bg-gradient-to-br from-secondary to-secondary">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <div class="relative flex items-center gap-4">
                            <div class="h-12 w-12 rounded-full bg-white/15 flex items-center justify-center">
                                <x-heroicon name="book-open" class="h-7 w-7" />
                            </div>
                            <div>
                                <div class="text-xs uppercase">Pelajaran</div>
                                <div class="text-lg font-bold">Jelajahi</div>
                            </div>
                        </div>
                        <x-heroicon name="book-open" class="absolute right-2 top-2 h-20 w-20 opacity-10 text-text-light" />
                    </a>
                    <a href="{{ route('profile.edit') }}" class="relative rounded-xl overflow-hidden p-4 text-text-light shadow hover:shadow-lg transition bg-gradient-to-br from-info to-info">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <div class="relative flex items-center gap-4">
                            <div class="h-12 w-12 rounded-full bg-white/15 flex items-center justify-center">
                                <x-heroicon name="cog" class="h-7 w-7" />
                            </div>
                            <div>
                                <div class="text-xs uppercase">Profil</div>
                                <div class="text-lg font-bold">Pengaturan</div>
                            </div>
                        </div>
                        <x-heroicon name="cog" class="absolute right-2 top-2 h-20 w-20 opacity-10 text-text-light" />
                    </a>
                </div>
            </div>

            @role('admin')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <x-stat-card title="Total Siswa" :value="$total_students ?? 0" icon="academic-cap" scheme="tertiary-secondary" />
                <x-stat-card title="Total Guru" :value="$total_teachers ?? 0" icon="user-group" scheme="secondary-success" />
                <x-stat-card title="Total Pelajaran" :value="$total_courses ?? 0" icon="rectangle-stack" scheme="accent-primary" />
            </div>

            <!-- Users List -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Daftar Pengguna</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Peran</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Bergabung</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-secondary/10 text-secondary capitalize">
                                            {{ $user->getRoleNames()->first() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>

            @if(isset($recent_courses) && $recent_courses->count() > 0)
            <div class="mt-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Pelajaran Terbaru</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recent_courses as $course)
                    <div class="bg-white overflow-hidden rounded-2xl shadow hover:shadow-xl transition transform hover:-translate-y-0.5 flex flex-col h-full">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-40 object-cover">
                        @else
                            <div class="w-full h-40 bg-gradient-to-br from-[#6C5CE7] to-[#4ECDC4] flex items-center justify-center text-white">
                                <span class="text-4xl">📚</span>
                            </div>
                        @endif
                        <div class="p-6 flex-1 flex flex-col">
                            <h4 class="font-bold text-lg mb-1">{{ $course->title }}</h4>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $course->description }}</p>
                            <div class="mt-auto">
                                <div class="text-xs text-gray-500 mb-2">Instruktur: {{ optional($course->teacher)->name ?? '-' }}</div>
                                <a href="{{ route('courses.show', $course) }}" class="block w-full text-center bg-tertiary hover:bg-[#5a4dd6] text-white font-semibold py-2 rounded-lg">
                                    Lihat Kursus
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @endrole

            @role('teacher')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <x-stat-card title="Pelajaran Saya" :value="$my_courses ?? 0" icon="book-open" scheme="tertiary-secondary" />
                <x-stat-card title="Siswa Terdaftar" :value="$my_students ?? 0" icon="user-group" scheme="info-tertiary" />
            </div>

            @if(isset($teacher_courses) && $teacher_courses->count() > 0)
            <h3 class="text-lg font-bold text-gray-900 mb-4">Pelajaran Saya</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($teacher_courses as $course)
                <div class="bg-white overflow-hidden rounded-2xl shadow hover:shadow-xl transition transform hover:-translate-y-0.5 flex flex-col h-full">
                    @if($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-40 object-cover">
                    @else
                        <div class="w-full h-40 bg-gradient-to-br from-[#6C5CE7] to-[#4ECDC4] flex items-center justify-center text-white">
                            <span class="text-4xl">📚</span>
                        </div>
                    @endif
                    <div class="p-6 flex-1 flex flex-col">
                        <h4 class="font-bold text-lg mb-1">{{ $course->title }}</h4>
                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $course->description }}</p>
                        <div class="mt-auto">
                            <a href="{{ route('courses.show', $course) }}" class="block w-full text-center bg-tertiary hover:bg-[#5a4dd6] text-white font-semibold py-2 rounded-lg">
                                Lihat Kursus
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Student Progress Table -->
            <div class="bg-surface overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-text-dark">Daftar Siswa & Nilai</h3>
                    </div>
                    @if(isset($teacher_courses) && count($teacher_courses) > 0)
                        @foreach($teacher_courses as $course)
                            <div class="mb-8 last:mb-0">
                                <div class="flex items-center justify-between bg-background p-3 rounded-lg mb-2">
                                    <div class="flex items-center gap-2">
                                        <x-heroicon name="rectangle-stack" class="h-5 w-5 text-tertiary" />
                                        <h4 class="text-md font-bold text-text-dark">{{ $course->title }}</h4>
                                    </div>
                                </div>
                                @if($course->students->isEmpty())
                                    <p class="text-gray-500 text-sm italic p-2">Belum ada siswa terdaftar.</p>
                                @else
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full text-sm divide-y divide-gray-100 rounded-lg">
                                            <thead class="bg-background sticky top-0 z-10">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-semibold text-text-dark uppercase">Nama Siswa</th>
                                                    <th class="px-6 py-3 text-left text-xs font-semibold text-text-dark uppercase">Email</th>
                                                    <th class="px-6 py-3 text-left text-xs font-semibold text-text-dark uppercase">Progres</th>
                                                    <th class="px-6 py-3 text-left text-xs font-semibold text-text-dark uppercase">Nilai Kuis (Rata-rata)</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-surface divide-y divide-gray-100">
                                                @foreach($course->students as $student)
                                                <tr class="hover:bg-gray-50 transition">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center gap-2">
                                                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-tertiary/10 text-tertiary">
                                                                <x-heroicon name="user" class="h-4 w-4" />
                                                            </span>
                                                            <span class="text-sm font-medium text-text-dark">{{ $student->name }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="text-sm text-gray-600">{{ $student->email }}</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-40 bg-gray-200 rounded-full h-2">
                                                                <div class="h-2 rounded-full bg-gradient-to-r from-tertiary to-secondary" style="width: {{ $student->progress }}%"></div>
                                                            </div>
                                                            <span class="text-xs font-semibold text-text-dark">{{ $student->progress }}%</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @php $avg = $student->quiz_average; @endphp
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                                            @if($avg === '-') bg-gray-100 text-gray-500
                                                            @elseif($avg >= 85) bg-success/10 text-success
                                                            @elseif($avg >= 70) bg-warning/10 text-warning
                                                            @else bg-danger/10 text-danger
                                                            @endif">
                                                            {{ $avg === '-' ? 'Belum ada' : $avg }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-center py-4">Anda belum memiliki pelajaran.</p>
                    @endif
                </div>
            </div>
            @endrole

            @role('student')
            @if(isset($student_grade_levels) && count($student_grade_levels) > 0)
            <div class="mb-6">
                <div class="relative rounded-xl overflow-hidden p-4 text-text-light shadow bg-gradient-to-br from-secondary to-tertiary">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative flex items-center gap-4">
                        <div class="h-12 w-12 rounded-full bg-white/15 flex items-center justify-center">
                            <x-heroicon name="academic-cap" class="h-7 w-7" />
                        </div>
                        <div>
                            <div class="text-xs uppercase">Kelas Saya</div>
                            <div class="text-lg font-bold">
                                {{ implode(', ', $student_grade_levels->toArray()) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <x-stat-card title="Pelajaran Diikuti" :value="$enrolled_courses_count ?? 0" icon="book-open" scheme="info-tertiary" />
                <x-stat-card title="Pelajaran Selesai" :value="$completed_courses_count ?? 0" icon="rectangle-stack" scheme="success-secondary" />
            </div>

            <h3 class="text-lg font-bold text-gray-900 mb-4">Pelajaran Saya</h3>
            @if(isset($my_courses) && $my_courses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($my_courses as $course)
                    <div class="bg-white overflow-hidden rounded-2xl shadow hover:shadow-xl transition transform hover:-translate-y-0.5 flex flex-col h-full">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-40 object-cover">
                        @else
                            <div class="w-full h-40 bg-gradient-to-br from-[#6C5CE7] to-[#4ECDC4] flex items-center justify-center text-white">
                                <span class="text-4xl">📚</span>
                            </div>
                        @endif
                        <div class="p-6 flex-1 flex flex-col">
                            <h4 class="font-bold text-lg mb-1">{{ $course->title }}</h4>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $course->description }}</p>
                            
                            <div class="mt-auto">
                                <div class="text-xs text-gray-500 mb-2">Instruktur: {{ $course->teacher->name }}</div>
                                @if($course->pivot->completed_at)
                                    <span class="block w-full text-center bg-green-100 text-green-800 text-sm font-semibold py-2 rounded-lg">
                                        Selesai
                                    </span>
                                @else
                                    <a href="{{ route('learning.course', $course) }}" class="block w-full text-center bg-tertiary hover:bg-[#5a4dd6] text-white font-semibold py-2 rounded-lg">
                                        Lanjut Belajar
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white overflow-hidden rounded-2xl shadow p-6 text-center">
                    <p class="text-gray-500 mb-4">Anda belum mengikuti pelajaran apapun.</p>
                    <a href="{{ route('courses.index') }}" class="inline-block px-4 py-2 rounded-lg bg-tertiary text-white hover:bg-[#5a4dd6]">Jelajahi Pelajaran</a>
                </div>
            @endif
            @endrole
        </div>
    </div>
</x-app-layout>
