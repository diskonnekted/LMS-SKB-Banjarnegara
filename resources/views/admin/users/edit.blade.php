<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Pengguna
            </h2>
            <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-800">Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
                    @endif
                    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Password Baru</label>
                                <input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Peran</label>
                            @php $roleName = $user->roles->pluck('name')->first(); @endphp
                            <select name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">-- Pilih Peran --</option>
                                <option value="student" @selected(old('role', $roleName)=='student')>Siswa</option>
                                <option value="teacher" @selected(old('role', $roleName)=='teacher')>Guru</option>
                                <option value="admin" @selected(old('role', $roleName)=='admin')>Admin</option>
                            </select>
                            @error('role') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        @if($roleName === 'student')
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Informasi Kelas Siswa</h3>
                            @if(isset($enrolledCourses) && $enrolledCourses->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Pelajaran</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Kelas</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Guru</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-100">
                                            @foreach($enrolledCourses as $course)
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-900">{{ $course->title }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-600">{{ $course->grade_level ?? '-' }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-600">{{ optional($course->teacher)->name ?? '-' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-sm text-gray-600">Belum ada kelas/pelajaran yang diikuti.</p>
                            @endif
                        </div>
                        @endif

                        @if($roleName === 'teacher')
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Pelajaran Diampu Guru</h3>
                            @if(isset($teachingCourses) && $teachingCourses->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Pelajaran</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Kategori</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Kelas</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-100">
                                            @foreach($teachingCourses as $course)
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-900">{{ $course->title }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-600">{{ optional($course->category)->name ?? '-' }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-600">{{ $course->grade_level ?? '-' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-sm text-gray-600">Belum ada pelajaran yang diampu.</p>
                            @endif
                        </div>
                        @endif

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-100 rounded-md text-gray-700 hover:bg-gray-200">Batal</a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
