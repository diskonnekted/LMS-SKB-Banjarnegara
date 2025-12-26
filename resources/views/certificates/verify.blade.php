<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Verifikasi Sertifikat
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-sm text-gray-500">Kode Sertifikat</div>
                            <div class="text-lg font-bold">{{ $certificate->certificate_code }}</div>
                        </div>
                        <a href="{{ route('profiles.public', $user) }}" class="text-indigo-600 hover:text-indigo-800">Lihat Profil</a>
                    </div>
                    
                    <div class="mt-6">
                        <div class="text-sm text-gray-500">Nama Siswa</div>
                        <div class="text-xl font-semibold">{{ $user->name }}</div>
                    </div>
                    
                    <div class="mt-4">
                        <div class="text-sm text-gray-500">Kursus</div>
                        <div class="text-lg font-medium">{{ $course->title }}</div>
                    </div>

                    <div class="mt-4">
                        <div class="text-sm text-gray-500">Tanggal Diterbitkan</div>
                        <div class="text-lg">{{ $certificate->created_at->format('d M Y') }}</div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('certificates.download', $course) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm">
                            Unduh Sertifikat PDF
                        </a>
                        <a href="{{ route('learning.course', $course) }}" class="inline-flex items-center ml-3 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md shadow-sm">
                            Lihat Kursus
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
