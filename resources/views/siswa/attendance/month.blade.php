@extends('layouts.dashboard')

@section('title', 'Kehadiran Bulanan - ' . $dateObj->format('F Y'))

@section('header', 'Kehadiran Bulanan')

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
            <h2 class="text-xl font-semibold text-gray-800 mt-2">Kehadiran Bulan {{ $dateObj->format('F Y') }}</h2>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('siswa.attendance.month', [
                'month' => $dateObj->copy()->subMonth()->format('m'),
                'year' => $dateObj->copy()->subMonth()->format('Y')
            ]) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-chevron-left"></i>
            </a>
            <a href="{{ route('siswa.attendance.month', [
                'month' => now()->format('m'),
                'year' => now()->format('Y')
            ]) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Bulan Ini
            </a>
            <a href="{{ route('siswa.attendance.month', [
                'month' => $dateObj->copy()->addMonth()->format('m'),
                'year' => $dateObj->copy()->addMonth()->format('Y')
            ]) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>
    </div>
    
    <!-- Monthly Calendar -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-800">Kalender Kehadiran</h3>
            <p class="text-sm text-gray-500">Klik pada tanggal untuk melihat detail kehadiran pada hari tersebut.</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-7 gap-4">
                <div class="text-center text-sm font-medium text-gray-600">Minggu</div>
                <div class="text-center text-sm font-medium text-gray-600">Senin</div>
                <div class="text-center text-sm font-medium text-gray-600">Selasa</div>
                <div class="text-center text-sm font-medium text-gray-600">Rabu</div>
                <div class="text-center text-sm font-medium text-gray-600">Kamis</div>
                <div class="text-center text-sm font-medium text-gray-600">Jumat</div>
                <div class="text-center text-sm font-medium text-gray-600">Sabtu</div>
                
                @php
                    $startOfMonth = $dateObj->copy()->startOfMonth();
                    $endOfMonth = $dateObj->copy()->endOfMonth();
                    
                    // Fill in empty days at the beginning
                    $startDay = $startOfMonth->dayOfWeek;
                    for ($i = 0; $i < $startDay; $i++) {
                        echo '<div class="p-2"></div>';
                    }
                    
                    // Calendar days
                    for ($day = 1; $day <= $endOfMonth->day; $day++) {
                        $date = $dateObj->copy()->setDay($day);
                        $dateFormatted = $date->format('Y-m-d');
                        $hasAttendance = isset($attendanceRecords[$dateFormatted]);
                        $isToday = $date->isToday();
                        $isWeekend = $date->isWeekend();
                        
                        // Determine status colors
                        $bgClass = 'bg-white';
                        $textClass = 'text-gray-800';
                        $borderClass = 'border-gray-200';
                        
                        if ($isToday) {
                            $bgClass = 'bg-indigo-50';
                            $borderClass = 'border-indigo-300';
                        }
                        
                        if ($isWeekend) {
                            $textClass = 'text-gray-400';
                        }
                        
                        if ($hasAttendance) {
                            $statuses = $attendanceRecords[$dateFormatted]->pluck('status')->toArray();
                            
                            if (in_array('absent', $statuses)) {
                                $bgClass = 'bg-red-50';
                                $borderClass = 'border-red-200';
                            } elseif (in_array('late', $statuses)) {
                                $bgClass = 'bg-yellow-50';
                                $borderClass = 'border-yellow-200';
                            } elseif (in_array('excused', $statuses)) {
                                $bgClass = 'bg-blue-50';
                                $borderClass = 'border-blue-200';
                            } elseif (in_array('present', $statuses)) {
                                $bgClass = 'bg-green-50';
                                $borderClass = 'border-green-200';
                            }
                        }
                        
                        echo '<div class="min-h-[100px] p-2 border rounded-lg ' . $bgClass . ' ' . $borderClass . ' hover:shadow-sm cursor-pointer">';
                        echo '<div class="font-medium ' . $textClass . '">' . $day . '</div>';
                        
                        if ($hasAttendance) {
                            $records = $attendanceRecords[$dateFormatted];
                            foreach ($records as $index => $record) {
                                if ($index < 2) {
                                    $statusClass = '';
                                    switch ($record->status) {
                                        case 'present': $statusClass = 'text-green-700'; break;
                                        case 'absent': $statusClass = 'text-red-700'; break;
                                        case 'late': $statusClass = 'text-yellow-700'; break;
                                        case 'excused': $statusClass = 'text-blue-700'; break;
                                    }
                                    
                                    $statusText = [
                                        'present' => 'Hadir',
                                        'absent' => 'Tidak Hadir',
                                        'late' => 'Terlambat',
                                        'excused' => 'Izin',
                                    ][$record->status] ?? 'Unknown';
                                    
                                    echo '<div class="text-xs mt-1 ' . $statusClass . '">';
                                    echo $record->attendance->subject->name ?? '';
                                    echo ' - ' . $statusText;
                                    echo '</div>';
                                } else if ($index == 2) {
                                    $remaining = count($records) - 2;
                                    echo '<div class="text-xs mt-1 text-gray-500">+' . $remaining . ' lainnya</div>';
                                    break;
                                }
                            }
                        }
                        
                        echo '</div>';
                    }
                    
                    // Fill in empty days at the end
                    $endDay = $endOfMonth->dayOfWeek;
                    $remainingDays = 6 - $endDay;
                    for ($i = 0; $i < $remainingDays; $i++) {
                        echo '<div class="p-2"></div>';
                    }
                @endphp
            </div>
        </div>
    </div>
    
    <!-- Legend -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="p-4">
            <h3 class="text-sm font-medium text-gray-700 mb-3">Keterangan:</h3>
            <div class="flex flex-wrap gap-4">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-50 border border-green-200 mr-2"></div>
                    <span class="text-sm text-gray-600">Hadir</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-50 border border-red-200 mr-2"></div>
                    <span class="text-sm text-gray-600">Tidak Hadir</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-50 border border-yellow-200 mr-2"></div>
                    <span class="text-sm text-gray-600">Terlambat</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-50 border border-blue-200 mr-2"></div>
                    <span class="text-sm text-gray-600">Izin</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-indigo-50 border border-indigo-300 mr-2"></div>
                    <span class="text-sm text-gray-600">Hari Ini</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily attendance details will be shown in a modal or expanded section -->
@endsection
