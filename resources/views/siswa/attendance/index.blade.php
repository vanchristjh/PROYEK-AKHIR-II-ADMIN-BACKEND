@extends('layouts.dashboard')

@section('title', 'Data Kehadiran')

@section('header', 'Data Kehadiran')

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
    <!-- Header with animation -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-clipboard-check text-9xl"></i>
        </div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Data Kehadiran</h2>
            <p class="text-blue-100">Rekap kehadiran Anda dalam pembelajaran</p>
        </div>
    </div>

    <!-- Attendance Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Kehadiran Bulan Ini</p>
                    <h3 class="text-xl font-bold text-gray-800">{{ $attendanceSummary['present'] ?? 0 }}</h3>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check text-green-500"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Kehadiran: {{ $attendanceSummary['attendance_rate'] ?? 0 }}%</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tidak Hadir</p>
                    <h3 class="text-xl font-bold text-gray-800">{{ $attendanceSummary['absent'] ?? 0 }}</h3>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-times text-red-500"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Dari total {{ $attendanceSummary['total'] ?? 0 }} pertemuan bulan ini</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Terlambat</p>
                    <h3 class="text-xl font-bold text-gray-800">{{ $attendanceSummary['late'] ?? 0 }}</h3>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-clock text-yellow-500"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Terlambat: {{ $attendanceSummary['late'] ?? 0 }} kali bulan ini</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Izin</p>
                    <h3 class="text-xl font-bold text-gray-800">{{ $attendanceSummary['excused'] ?? 0 }}</h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-file-alt text-blue-500"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Izin resmi yang tercatat</p>
        </div>
    </div>

    <!-- Monthly View Button -->
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-700">Riwayat Kehadiran</h3>
        <a href="{{ route('siswa.attendance.month') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors inline-flex items-center">
            <i class="fas fa-calendar-alt mr-2"></i> Tampilan Bulanan
        </a>
    </div>

    <!-- Recent Attendance Records -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        @if($recentAttendance->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentAttendance as $record)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $record->attendance->date->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $record->attendance->subject->name ?? 'N/A' }}</div>
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
        @else
            <div class="p-6 text-center">
                <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                    <i class="fas fa-clipboard-check text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data kehadiran</h3>
                <p class="text-gray-500">Belum ada data kehadiran yang tercatat untuk Anda.</p>
            </div>
        @endif
    </div>

    <!-- Subject Attendance Links -->
    <h3 class="text-lg font-semibold text-gray-700 mb-4">Kehadiran Per Mata Pelajaran</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @forelse($subjects as $subject)
            <a href="{{ route('siswa.attendance.by-subject', $subject) }}" class="bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition border border-gray-100">
                <h4 class="font-medium text-gray-800 mb-1">{{ $subject->name }}</h4>
                <p class="text-sm text-gray-500">{{ $subject->code }}</p>
                <div class="mt-3 flex items-center justify-between">
                    <span class="text-xs text-gray-500">Lihat detail kehadiran</span>
                    <i class="fas fa-chevron-right text-xs text-indigo-500"></i>
                </div>
            </a>
        @empty
            <div class="col-span-3 bg-gray-50 p-6 text-center rounded-lg">
                <p class="text-gray-500">Tidak ada data mata pelajaran.</p>
            </div>
        @endforelse
    </div>
@endsection
