<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dasbor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600 mt-1">Anda masuk sebagai <span class="font-bold capitalize">{{ Auth::user()->getRoleNames()->first() }}</span>.</p>
                </div>
            </div>

            @role('admin')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Students -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 text-sm uppercase font-semibold">Total Siswa</div>
                    <div class="text-3xl font-bold text-gray-800 mt-2">{{ $total_students ?? 0 }}</div>
                </div>

                <!-- Total Teachers -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-gray-500 text-sm uppercase font-semibold">Total Guru</div>
                    <div class="text-3xl font-bold text-gray-800 mt-2">{{ $total_teachers ?? 0 }}</div>
                </div>

                <!-- Total Courses -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-gray-500 text-sm uppercase font-semibold">Total Kursus</div>
                    <div class="text-3xl font-bold text-gray-800 mt-2">{{ $total_courses ?? 0 }}</div>
                </div>
            </div>

            <!-- Users List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Pengguna</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 capitalize">
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
            @endrole

            @role('teacher')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- My Courses -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-gray-500 text-sm uppercase font-semibold">Kursus Saya</div>
                    <div class="text-3xl font-bold text-gray-800 mt-2">{{ $my_courses ?? 0 }}</div>
                </div>

                <!-- My Students -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 text-sm uppercase font-semibold">Siswa Terdaftar</div>
                    <div class="text-3xl font-bold text-gray-800 mt-2">{{ $my_students ?? 0 }}</div>
                </div>
            </div>
            @endrole

            @role('student')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Enrolled -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 text-sm uppercase font-semibold">Kursus Diikuti</div>
                    <div class="text-3xl font-bold text-gray-800 mt-2">{{ $enrolled_courses_count ?? 0 }}</div>
                </div>

                <!-- Completed -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-gray-500 text-sm uppercase font-semibold">Kursus Selesai</div>
                    <div class="text-3xl font-bold text-gray-800 mt-2">{{ $completed_courses_count ?? 0 }}</div>
                </div>
            </div>

            <h3 class="text-xl font-bold text-gray-800 mb-4">Kelas Saya</h3>
            @if(isset($my_courses) && $my_courses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($my_courses as $course)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                        @if($course->thumbnail)
                            <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                        @else
                            <img src="{{ asset('images/skb2.jpg') }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                        @endif
                        <div class="p-6">
                            <h4 class="text-lg font-bold text-gray-800 mb-2 line-clamp-1">{{ $course->title }}</h4>
                            <p class="text-sm text-gray-500 mb-4">Pengajar: {{ $course->teacher->name }}</p>
                            <a href="{{ route('learning.course', $course) }}" class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                                Lanjut Belajar
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-center">
                    <p class="text-gray-500 mb-4">Anda belum mendaftar di kursus apapun.</p>
                    <a href="{{ route('home') }}#courses" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded transition duration-300">
                        Cari Kursus
                    </a>
                </div>
            @endif
            @endrole
        </div>
    </div>
</x-app-layout>
