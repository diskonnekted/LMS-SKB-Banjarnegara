<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Profil Publik: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center gap-4">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="h-20 w-20 rounded-full object-cover">
                        @else
                            <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                                {{ substr($user->name,0,1) }}
                            </div>
                        @endif
                        <div>
                            <h3 class="text-2xl font-bold">{{ $user->name }}</h3>
                            <p class="text-gray-500">{{ $user->bio }}</p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h4 class="text-lg font-semibold mb-4">Sertifikat Tercapai</h4>
                        @if($certificates->isEmpty())
                            <p class="text-gray-500">Belum ada sertifikat.</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($certificates as $cert)
                                    <div class="border rounded-lg p-4 bg-gray-50">
                                        <div class="font-bold text-gray-900">{{ $cert->course->title }}</div>
                                        <div class="text-sm text-gray-500 mt-1">Diterbitkan: {{ $cert->created_at->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-400 mt-1">Kode: {{ $cert->certificate_code }}</div>
                                        <div class="mt-3 flex items-center justify-between">
                                            <a href="{{ route('certificates.verify', $cert->certificate_code) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Verifikasi</a>
                                            <a href="{{ route('certificates.download', $cert->course) }}" class="text-gray-600 hover:text-gray-800 text-sm">Unduh</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
