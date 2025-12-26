<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Pengguna
            </h2>
            <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-800">Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="text-sm text-gray-500">Nama</div>
                            <div class="text-base font-semibold text-gray-900">{{ $user->name }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Email</div>
                            <div class="text-base font-semibold text-gray-900">{{ $user->email }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Peran</div>
                            <div class="text-base font-semibold text-gray-900">{{ $user->roles->pluck('name')->implode(', ') ?: '-' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Dibuat</div>
                            <div class="text-base font-semibold text-gray-900">{{ $user->created_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Edit</a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

