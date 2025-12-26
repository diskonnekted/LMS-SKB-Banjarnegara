<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div>
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Sertifikat yang Telah Dicapai</h3>
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
                                        <div class="mt-2 text-sm text-gray-500">Kursus</div>
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
    </div>
</x-app-layout>
