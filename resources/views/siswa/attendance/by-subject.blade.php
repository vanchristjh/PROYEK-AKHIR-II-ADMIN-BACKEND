@extends('layouts.dashboard')

@section('title', 'Kehadiran ' . $subject->name)

@section('header', 'Kehadiran Per Mata Pelajaran')

@section('navigation')
    <li>
        <a href="{{ route('siswa.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-tachometer-alt text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.material.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-book text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Materi Pelajaran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.assignments.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-tasks text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Tugas</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.grades.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-star text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Nilai</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.attendance.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-3 text-white">
            <i class="fas fa-clipboard-check text-lg w-6"></i>
            <span class="ml-3">Kehadiran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.announcements.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-bullhorn text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('siswa.attendance.index') }}" class="text-indigo-600 hover:text-indigo-800 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Ringkasan
            </a>
            <h2 class="text-xl font-semibold text-gray-800 mt-2">Kehadiran {{ $subject->name }} ({{ $subject->code }})</h2>
        </div>
    </div>
    
    <!-- Subject Info Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="p-6 border-b">
            <div class="flex items-center">
                <div class="bg-indigo-100 rounded-full p-3 text-indigo-600 mr-4">
                    <i class="fas fa-book text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-800">{{ $subject->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $subject->code }}</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <p class="text-gray-700">
                {{ $subject->description ?? 'Tidak ada deskripsi untuk mata pelajaran ini.' }}
            </p>
            
            @if($subject->teachers->count() > 0)
                <div class="mt-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Guru Pengajar:</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($subject->teachers as $teacher)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-user-tie mr-1 text-gray-500"></i>
                                {{ $teacher->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Attendance Records -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 bg-gray-50 border-b">
            <h3 class="font-medium text-gray-800">Riwayat Kehadiran</h3>
        </div>
        
        @if($attendanceRecords->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($attendanceRecords as $record)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $record->attendance->date->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $record->attendance->date->format('l') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $record->attendance->teacher->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass = [
                                            'present' => 'bg-green-100 text-green-800',
                                            'absent' => 'bg-red-100 text-red-800',
                                            'late' => 'bg-yellow-100 text-yellow-800',
                                            'excused' => 'bg-blue-100 text-blue-800',
                                        ][$record->status] ?? 'bg-gray-100 text-gray-800';
                                        
                                        $statusText = [
                                            'present' => 'Hadir',
                                            'absent' => 'Tidak Hadir',
                                            'late' => 'Terlambat',
                                            'excused' => 'Izin',
                                        ][$record->status] ?? 'Unknown';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $record->notes ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-3">
                {{ $attendanceRecords->links() }}
            </div>
        @else
            <div class="p-6 text-center">
                <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                    <i class="fas fa-clipboard-check text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data kehadiran</h3>
                <p class="text-gray-500">Tidak ada data kehadiran yang tercatat untuk mata pelajaran ini.</p>
            </div>
        @endif
    </div>
@endsection
