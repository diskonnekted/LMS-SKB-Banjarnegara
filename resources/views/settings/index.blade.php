<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan Umum') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('settings.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Hero Title -->
                        <div class="mb-4">
                            <x-input-label for="hero_title" :value="__('Judul Hero')" />
                            <x-text-input id="hero_title" class="block mt-1 w-full" type="text" name="hero_title" :value="$settings['hero_title'] ?? 'Selamat Datang di LMS Kami'" required />
                        </div>

                        <!-- Hero Description -->
                        <div class="mb-4">
                            <x-input-label for="hero_description" :value="__('Deskripsi Hero')" />
                            <textarea id="hero_description" name="hero_description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ $settings['hero_description'] ?? 'Belajar kapan saja, di mana saja.' }}</textarea>
                        </div>

                        <!-- Organizer Name -->
                        <div class="mb-4">
                            <x-input-label for="organizer_name" :value="__('Nama Penyelenggara')" />
                            <x-text-input id="organizer_name" class="block mt-1 w-full" type="text" name="organizer_name" :value="$settings['organizer_name'] ?? 'SKB Institute'" required />
                        </div>

                        <div class="flex items-center gap-4 mt-4">
                            <x-primary-button>{{ __('Simpan Pengaturan') }}</x-primary-button>
                            
                            @if (session('success'))
                                <p class="text-sm text-green-600">{{ session('success') }}</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
