@extends('layouts.dashboard')

@section('title', 'Kelola Jadwal')

@section('header', 'Kelola Jadwal')

@section('navigation')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <!-- Header with animation -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-calendar-alt text-9xl"></i>
        </div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Kelola Jadwal</h2>
            <p class="text-blue-100">Mengelola jadwal untuk semua kelas dan guru</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md animate-fade-in">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(isset($message))
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-md">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">{{ $message }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden border border-gray-100">
        <div class="px-5 py-4 bg-gray-50 border-b border-gray-100">
            <div class="flex items-center">
                <i class="fas fa-filter text-gray-500 mr-2"></i>
                <h3 class="font-medium text-gray-700">Filter Jadwal</h3>
            </div>
        </div>
        <div class="p-5">
            <form action="{{ route('admin.schedule.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="classroom" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                    <select name="classroom" id="classroom" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">Semua Kelas</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ request('classroom') == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="teacher" class="block text-sm font-medium text-gray-700 mb-1">Guru</label>
                    <select name="teacher" id="teacher" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">Semua Guru</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                    <select name="subject" id="subject" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">Semua Mata Pelajaran</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="day" class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                    <select name="day" id="day" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">Semua Hari</option>
                        @foreach($dayNames as $key => $day)
                            <option value="{{ $key }}" {{ request('day') == $key ? 'selected' : '' }}>{{ $day }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2 lg:col-span-4 flex items-center space-x-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                        <i class="fas fa-search mr-2"></i> Filter
                    </button>
                    <a href="{{ route('admin.schedule.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:bg-gray-600">
                        <i class="fas fa-times mr-2"></i> Reset
                    </a>                    <div class="flex-grow"></div>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.schedule.calendar') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                            <i class="fas fa-calendar-week mr-2"></i> Kalender
                        </a>
                        <a href="{{ route('admin.schedule.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:bg-green-700">
                            <i class="fas fa-plus mr-2"></i> Tambah Jadwal
                        </a>
                        <a href="{{ route('admin.schedule.bulk-create') }}" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:bg-purple-700">
                            <i class="fas fa-calendar-plus mr-2"></i> Tambah Massal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Schedules Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <div class="px-5 py-4 bg-gray-50 border-b border-gray-100">
            <div class="flex items-center">
                <i class="fas fa-calendar-alt text-gray-500 mr-2"></i>
                <h3 class="font-medium text-gray-700">Daftar Jadwal</h3>
            </div>
        </div>

        <div class="overflow-x-auto">
            @if(isset($schedules) && $schedules->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($schedules as $schedule)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-calendar-day text-blue-500"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $dayNames[$schedule->day] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}</div>
                                </td>                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $schedule->classroom->name }}
                                        <a href="{{ route('admin.schedule.export-classroom', $schedule->classroom_id) }}" class="ml-2 text-xs text-blue-600 hover:text-blue-800" title="Lihat jadwal kelas">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $schedule->subject->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $schedule->subject->code }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $schedule->teacher->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $schedule->room ?? 'Belum ditentukan' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('admin.schedule.show', $schedule->id) }}" class="text-blue-600 hover:text-blue-900 focus:outline-none focus:underline">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.schedule.edit', $schedule->id) }}" class="text-indigo-600 hover:text-indigo-900 focus:outline-none focus:underline">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.schedule.destroy', $schedule->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 focus:outline-none focus:underline">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="px-6 py-4">
                    {{ $schedules->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <div class="mx-auto w-16 h-16 mb-4 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                        <i class="fas fa-calendar-times text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada jadwal</h3>
                    <p class="text-gray-500 max-w-md mx-auto mb-6">Belum ada jadwal yang ditambahkan atau tidak ada jadwal yang sesuai dengan filter yang dipilih.</p>
                    <a href="{{ route('admin.schedule.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i> Tambah Jadwal Baru
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<style>
    .animate-fade-in {
        animation: fade-in 0.6s ease-in-out;
    }
    
    @keyframes fade-in {
        0% {
            opacity: 0;
            transform: translateY(10px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush
