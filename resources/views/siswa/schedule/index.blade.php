@extends('layouts.dashboard')

@section('title', 'Jadwal Pelajaran')

@section('header', 'Jadwal Pelajaran')

@section('navigation')
    @include('siswa.partials.sidebar')
@endsection

@section('content')
    <!-- Search and Filter -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200/80 mb-6">
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-700">Filter Jadwal</h3>
        </div>
        <div class="p-4">
            <form action="{{ route('siswa.schedule.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="w-full md:w-auto">
                    <label for="day" class="block text-sm font-medium text-gray-600 mb-1">Hari</label>
                    <select name="day" id="day" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm">
                        <option value="">Semua Hari</option>
                        <option value="Senin" {{ request('day') == 'Senin' ? 'selected' : '' }}>Senin</option>
                        <option value="Selasa" {{ request('day') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="Rabu" {{ request('day') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="Kamis" {{ request('day') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="Jumat" {{ request('day') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                        <option value="Sabtu" {{ request('day') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                    </select>
                </div>
                
                <div class="w-full md:w-auto">
                    <label for="subject_id" class="block text-sm font-medium text-gray-600 mb-1">Mata Pelajaran</label>
                    <select name="subject_id" id="subject_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm">
                        <option value="">Semua Mata Pelajaran</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="w-full md:w-auto flex items-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Schedule Display -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200/80">
        <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-800">Jadwal Pelajaran</h3>
                <div class="flex items-center">
                    <span class="text-sm text-gray-600 mr-2">Kelas:</span>
                    <span class="text-sm font-semibold text-blue-700">{{ isset($classroom) ? $classroom->name : 'Tidak tersedia' }}</span>
                </div>
            </div>
        </div>

        @if(!isset($schedules) || (is_array($schedules) || $schedules instanceof \Countable) && count($schedules) == 0)
            <div class="p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-500 mb-2">Tidak ada jadwal ditemukan</h3>
                <p class="text-gray-400 mb-6">Coba ubah filter atau hubungi administrator jika diperlukan</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php 
                            $currentDay = '';
                            $today = date('N');
                            $dayMapping = ['1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu', '7' => 'Minggu'];
                            $todayName = $dayMapping[$today] ?? '';
                        @endphp
                        
                        @foreach($schedules as $day => $daySchedules)
                            @foreach($daySchedules as $schedule)
                                @php
                                    $isToday = $day === $todayName;
                                    $isCurrentClass = false;
                                    if($isToday) {
                                        $currentTime = date('H:i:s');
                                        $isCurrentClass = $currentTime >= $schedule->start_time && $currentTime <= $schedule->end_time;
                                    }
                                    $isNewSchedule = $schedule->created_at && $schedule->created_at->gt(now()->subDays(3));
                                @endphp
                                <tr class="{{ $isCurrentClass ? 'bg-green-50' : ($isToday ? 'bg-blue-50/30' : '') }} {{ $isNewSchedule ? 'border-l-4 border-blue-300' : '' }} hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($day !== $currentDay)
                                            @php $currentDay = $day; @endphp
                                            <span class="font-medium text-gray-900">{{ $day }}</span>
                                            @if($isToday)
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Hari ini
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="flex items-center">
                                            <i class="far fa-clock text-gray-400 mr-2"></i>
                                            <span class="text-sm text-gray-900">{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $schedule->subject->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $schedule->subject->code ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $schedule->teacher->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('siswa.schedule.day', ['day' => $schedule->day]) }}" class="text-blue-600 hover:text-blue-900 flex items-center justify-end">
                                            <span>Lihat Detail</span> <i class="fas fa-chevron-right ml-1 text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center text-sm text-gray-500">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    <span>Klik "Lihat Detail" untuk melihat informasi lengkap jadwal harian.</span>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit form when select fields change
        const daySelect = document.getElementById('day');
        const subjectSelect = document.getElementById('subject_id');
        
        if (daySelect) {
            daySelect.addEventListener('change', function() {
                this.form.submit();
            });
        }
        
        if (subjectSelect) {
            subjectSelect.addEventListener('change', function() {
                this.form.submit();
            });
        }
        
        // Highlight the current class
        const updateCurrentClass = function() {
            const now = new Date();
            const currentTime = now.getHours().toString().padStart(2, '0') + ':' + 
                               now.getMinutes().toString().padStart(2, '0') + ':' + 
                               now.getSeconds().toString().padStart(2, '0');
                               
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(function(row) {
                // Only process rows from today
                if (row.classList.contains('bg-blue-50/30')) {
                    const timeCell = row.querySelector('td:nth-child(2)');
                    if (timeCell) {
                        const timeText = timeCell.textContent.trim();
                        const timeParts = timeText.split(' - ');
                        if (timeParts.length === 2) {
                            const startTime = timeParts[0] + ':00';
                            const endTime = timeParts[1] + ':00';
                            
                            if (currentTime >= startTime && currentTime <= endTime) {
                                row.classList.add('bg-green-50');
                            } else {
                                row.classList.remove('bg-green-50');
                            }
                        }
                    }
                }
            });
        };
        
        // Update current class every minute
        updateCurrentClass();
        setInterval(updateCurrentClass, 60000);
    });
</script>
@endpush
