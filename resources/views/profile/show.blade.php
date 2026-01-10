<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Profil') }}
            </h2>
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                Edit Profil
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <div class="flex items-start gap-6">
                    <div>
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="h-24 w-24 rounded-full object-cover">
                        @else
                            <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center">
                                <x-heroicon name="user" class="h-10 w-10 text-gray-500" />
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-gray-500">Nama</div>
                            <div class="text-base font-semibold text-gray-900">{{ $user->name }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Email</div>
                            <div class="text-base font-semibold text-gray-900">{{ $user->email }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">No WhatsApp</div>
                            <div class="text-base font-semibold text-gray-900">{{ $user->whatsapp_number ?? '-' }}</div>
                        </div>

                        @hasrole('admin|teacher')
                        <div>
                            <div class="text-sm text-gray-500">NIP</div>
                            <div class="text-base font-semibold text-gray-900">{{ $user->nip ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Kelas yang Diampu</div>
                            <div class="text-base font-semibold text-gray-900">{{ $user->classes_taught ?? '-' }}</div>
                        </div>
                        @endhasrole

                        @role('student')
                        <div>
                            <div class="text-sm text-gray-500">Kelas</div>
                            <div class="text-base font-semibold text-gray-900">{{ $user->grade_level ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Tanggal Lahir</div>
                            <div class="text-base font-semibold text-gray-900">
                                {{ $user->date_of_birth ? $user->date_of_birth->format('d M Y') : '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Umur</div>
                            <div class="text-base font-semibold text-gray-900">
                                {{ $user->date_of_birth ? $user->date_of_birth->age.' tahun' : '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Tempat Lahir</div>
                            <div class="text-base font-semibold text-gray-900">{{ $user->place_of_birth ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Jenis Kelamin</div>
                            <div class="text-base font-semibold text-gray-900">
                                @if($user->gender === 'L')
                                    Laki-laki
                                @elseif($user->gender === 'P')
                                    Perempuan
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Sekolah</div>
                            <div class="text-base font-semibold text-gray-900">{{ $user->school_name ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">NISN</div>
                            <div class="text-base font-semibold text-gray-900">{{ $user->nisn ?? '-' }}</div>
                        </div>
                        @endrole
                        <div>
                            <div class="text-sm text-gray-500">NIK</div>
                            <div class="text-base font-semibold text-gray-900">{{ $user->nik ?? '-' }}</div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="text-sm text-gray-500">Bio</div>
                            <div class="text-sm text-gray-700 whitespace-pre-line">{{ $user->bio }}</div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="text-sm text-gray-500">Alamat</div>
                            <div class="text-sm text-gray-700 whitespace-pre-line">{{ $user->address ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Peran</div>
                            <div class="text-base font-semibold text-gray-900">{{ $user->getRoleNames()->implode(', ') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white shadow sm:rounded-lg">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-semibold text-gray-900">Pelajaran yang Diikuti</h3>
                    <a href="{{ route('courses.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Lihat Semua</a>
                </div>
                @if($user->enrolledCourses->isEmpty())
                    <p class="mt-2 text-sm text-gray-500">Belum mengikuti pelajaran.</p>
                @else
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($user->enrolledCourses as $course)
                            <div class="border rounded-lg overflow-hidden bg-gray-50">
                                <div class="p-4">
                                    <div class="text-base font-semibold text-gray-900">{{ $course->title }}</div>
                                    <div class="mt-1 text-sm text-gray-600">Instruktur: {{ optional($course->teacher)->name ?? '-' }}</div>
                                    @if($course->grade_level)
                                        <div class="mt-1 text-sm text-gray-600">Kelas: {{ $course->grade_level }}</div>
                                    @endif
                                </div>
                                <div class="px-4 py-3 bg-white border-t flex items-center justify-between">
                                    <a href="{{ route('learning.course', $course) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Buka</a>
                                    @if($course->pivot && $course->pivot->completed_at)
                                        <span class="text-xs font-semibold text-green-700">Selesai</span>
                                    @else
                                        <span class="text-xs font-semibold text-gray-600">Berjalan</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="p-6 bg-white shadow sm:rounded-lg">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-semibold text-gray-900">Sertifikat</h3>
                    <a href="{{ route('profiles.public', $user) }}" class="text-sm text-indigo-600 hover:text-indigo-800">Lihat Profil Publik</a>
                </div>
                @if($certificates->isEmpty())
                    <p class="mt-2 text-sm text-gray-500">Belum ada sertifikat.</p>
                @else
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($certificates as $cert)
                            <div class="border rounded-lg overflow-hidden bg-gray-50">
                                <div class="p-4">
                                    <div class="text-sm text-gray-500">Kode</div>
                                    <div class="text-base font-semibold text-gray-900">{{ $cert->certificate_code }}</div>
                                    <div class="mt-2 text-sm text-gray-500">Pelajaran</div>
                                    <div class="text-base text-gray-900">{{ $cert->course->title }}</div>
                                    <div class="mt-2 text-sm text-gray-500">Diterbitkan</div>
                                    <div class="text-sm text-gray-700">{{ $cert->created_at->format('d M Y') }}</div>
                                </div>
                                <div class="px-4 py-3 bg-white border-t flex items-center justify-between">
                                    <a href="{{ route('certificates.verify', $cert->certificate_code) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Verifikasi</a>
                                    <a href="{{ route('certificates.download', $cert->course) }}" class="text-gray-700 hover:text-gray-900 text-sm">Unduh PDF</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
