@extends('layouts.dashboard')

@section('title', 'Detail Kehadiran')

@section('header', 'Detail Kehadiran')

@section('navigation')
    <li>
        <a href="{{ route('guru.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-indigo-700 transition-all duration-200">
                <i class="fas fa-tachometer-alt text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.materials.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-green-700/50 transition-all duration-200">
                <i class="fas fa-book text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Materi Pelajaran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.assignments.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-green-700/50 transition-all duration-200">
                <i class="fas fa-tasks text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Tugas</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.grades.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-purple-700/50 transition-all duration-200">
                <i class="fas fa-star text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Penilaian</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.attendance.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-2.5 group relative text-white">
            <div class="p-1.5 rounded-lg bg-purple-800 transition-all duration-200">
                <i class="fas fa-clipboard-check text-lg w-5 h-5 flex items-center justify-center text-white"></i>
            </div>
            <span class="ml-3">Kehadiran</span>
            <span class="absolute inset-y-0 left-0 w-1 bg-purple-400 rounded-tr-md rounded-br-md"></span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.announcements.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-red-700/50 transition-all duration-200">
                <i class="fas fa-bullhorn text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="mb-6">
        <a href="{{ route('guru.attendance.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition-colors">
            <i class="fas fa-chevron-left mr-2 text-sm"></i>
            <span>Kembali ke Daftar Kehadiran</span>
        </a>
    </div>

    <!-- Header with details -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 mb-6">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-white">
            <div class="flex items-start justify-between">
                <div class="flex items-start">
                    <div class="bg-purple-100 text-purple-600 p-3 rounded-full mr-4">
                        <i class="fas fa-clipboard-check text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">
                            {{ $attendance->subject->name }} - {{ $attendance->classroom->name }}
                        </h1>
                        <div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-day mr-1"></i>
                                {{ $attendance->date->format('d M Y') }}
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $attendance->date->format('l') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('guru.attendance.edit', $attendance) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors flex items-center gap-1">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('guru.attendance.destroy', $attendance) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kehadiran ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors flex items-center gap-1">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Attendance Summary -->
        <div class="p-6 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Ringkasan Kehadiran</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $summary = [
                        'total' => $attendance->records->count(),
                        'present' => $attendance->records->where('status', 'present')->count(),
                        'absent' => $attendance->records->where('status', 'absent')->count(),
                        'late' => $attendance->records->where('status', 'late')->count(),
                        'excused' => $attendance->records->where('status', 'permitted')->count() + $attendance->records->where('status', 'sick')->count(),
                    ];
                    
                    $attendance_rate = $summary['total'] > 0 
                        ? round((($summary['present'] + $summary['late']) / $summary['total']) * 100, 1) 
                        : 0;
                @endphp
                
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Hadir</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $summary['present'] }}</p>
                        </div>
                        <div class="bg-green-100 p-2 rounded-full h-10 w-10 flex items-center justify-center">
                            <i class="fas fa-check text-green-500"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-red-500">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Tidak Hadir</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $summary['absent'] }}</p>
                        </div>
                        <div class="bg-red-100 p-2 rounded-full h-10 w-10 flex items-center justify-center">
                            <i class="fas fa-times text-red-500"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-yellow-500">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Terlambat</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $summary['late'] }}</p>
                        </div>
                        <div class="bg-yellow-100 p-2 rounded-full h-10 w-10 flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-500"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Izin/Sakit</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $summary['excused'] }}</p>
                        </div>
                        <div class="bg-blue-100 p-2 rounded-full h-10 w-10 flex items-center justify-center">
                            <i class="fas fa-file-alt text-blue-500"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center">
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ $attendance_rate }}%"></div>
                    </div>
                    <span class="ml-4 text-sm font-medium text-gray-700">{{ $attendance_rate }}% Kehadiran</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Attendance Records -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-800">Daftar Kehadiran Siswa</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($attendance->records as $index => $record)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $index + 1 }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $record->student->name }}</div>
                                <div class="text-xs text-gray-500">{{ $record->student->nis ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClass = [
                                        'present' => 'bg-green-100 text-green-800',
                                        'absent' => 'bg-red-100 text-red-800',
                                        'late' => 'bg-yellow-100 text-yellow-800',
                                        'sick' => 'bg-blue-100 text-blue-800',
                                        'permitted' => 'bg-blue-100 text-blue-800',
                                    ][$record->status] ?? 'bg-gray-100 text-gray-800';
                                    
                                    $statusText = [
                                        'present' => 'Hadir',
                                        'absent' => 'Tidak Hadir',
                                        'late' => 'Terlambat',
                                        'sick' => 'Sakit',
                                        'permitted' => 'Izin',
                                    ][$record->status] ?? 'Unknown';
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $record->notes ?? '-' }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Tidak ada data kehadiran siswa
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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