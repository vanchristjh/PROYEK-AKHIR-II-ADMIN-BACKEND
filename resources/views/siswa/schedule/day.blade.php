@extends('layouts.dashboard')

@section('title', 'Jadwal Hari ' . $dayName)

@section('header', 'Jadwal Hari ' . $dayName)

@section('navigation')
    @include('siswa.partials.sidebar')
@endsection

@section('content')
    <!-- Enhanced Header with animation -->
    <div class="bg-gradient-to-r from-blue-500 to-cyan-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-calendar-day text-9xl"></i>
        </div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute right-1/3 -top-12 w-36 h-36 bg-blue-300/20 rounded-full blur-3xl"></div>
        <div class="relative z-10">
            <div class="flex items-center">
                <div class="bg-white/20 p-2 rounded-lg shadow-inner backdrop-blur-sm mr-4">
                    <i class="fas fa-calendar-day text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold mb-1">Jadwal Hari {{ $dayName }}</h2>
                    <p class="text-blue-100">Kelas {{ $classroom->name }} | Semester {{ $classroom->semester }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <a href="{{ route('siswa.schedule.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
            <i class="fas fa-chevron-left mr-2 text-sm"></i>
            <span>Kembali ke Jadwal Mingguan</span>
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center space-x-4 mb-6">
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-800">Jadwal Hari {{ $dayName }}</h2>
        </div>
        
        @if(count($schedules) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Time table -->
                <div class="lg:col-span-8">
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Waktu</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($schedules as $schedule)
                                    @php
                                        $isCurrentClass = false;
                                        if($day == date('N')) {
                                            $currentTime = date('H:i:s');
                                            $isCurrentClass = $currentTime >= $schedule->start_time && $currentTime <= $schedule->end_time;
                                        }
                                        $isNewSchedule = $schedule->created_at && $schedule->created_at->gt(now()->subDays(3));
                                    @endphp
                                    <tr class="{{ $isCurrentClass ? 'bg-green-50' : ($isNewSchedule ? 'bg-blue-50' : 'hover:bg-gray-50') }} transition-colors duration-200">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8 rounded-full {{ 
                                                    $isCurrentClass ? 'bg-green-100 text-green-600' : 
                                                    ($isNewSchedule ? 'bg-blue-100 text-blue-600' : 'bg-blue-100 text-blue-600') 
                                                }} flex items-center justify-center">
                                                    <i class="{{ 
                                                        $isCurrentClass ? 'fas fa-play-circle' : 
                                                        ($isNewSchedule ? 'fas fa-plus-circle' : 'fas fa-clock') 
                                                    }}"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                                                    </div>
                                                    @if($isCurrentClass)
                                                        <div class="text-xs text-green-600 font-medium">Sedang Berlangsung</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $schedule->subject->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $schedule->subject->code ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $schedule->teacher->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $schedule->room ?? 'N/A' }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Right sidebar with summary -->
                <div class="lg:col-span-4">
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Ringkasan Hari {{ $dayName }}</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Total Mata Pelajaran</p>
                                    <p class="text-lg font-semibold text-gray-800">{{ count($schedules) }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Total Jam Pelajaran</p>
                                    @php
                                        $totalMinutes = 0;
                                        foreach ($schedules as $schedule) {
                                            $start = \Carbon\Carbon::parse($schedule->start_time);
                                            $end = \Carbon\Carbon::parse($schedule->end_time);
                                            $totalMinutes += $end->diffInMinutes($start);
                                        }
                                        $hours = floor($totalMinutes / 60);
                                        $minutes = $totalMinutes % 60;
                                    @endphp
                                    <p class="text-lg font-semibold text-gray-800">{{ $hours }} jam {{ $minutes > 0 ? $minutes . ' menit' : '' }}</p>
                                </div>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <h4 class="text-sm font-medium text-gray-600 mb-2">Guru yang Mengajar:</h4>
                                <div class="space-y-2">
                                    @php
                                        $teachers = $schedules->pluck('teacher')->unique('id')->filter();
                                    @endphp
                                    
                                    @foreach($teachers as $teacher)
                                        <div class="flex items-center p-2 bg-white rounded-lg border border-gray-100">
                                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-800">{{ $teacher->name }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-10">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-500 mb-2">Tidak ada jadwal untuk hari {{ $dayName }}</h3>
                <p class="text-gray-400 mb-6">Kembali ke jadwal mingguan untuk melihat hari lainnya</p>
                <a href="{{ route('siswa.schedule.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-md text-white text-sm">
                    <i class="fas fa-calendar-alt mr-2"></i> Lihat Jadwal Mingguan
                </a>
            </div>
        @endif
    </div>
@endsection

@push('styles')
<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    
    @keyframes fadeIn {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    
    /* Additional highlight for current class */
    .bg-green-50 {
        position: relative;
        overflow: hidden;
    }
    
    .bg-green-50::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background-color: #10B981;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update current class highlight
        function updateCurrentClass() {
            const now = new Date();
            const currentTime = now.getHours().toString().padStart(2, '0') + ':' + 
                               now.getMinutes().toString().padStart(2, '0') + ':' + 
                               now.getSeconds().toString().padStart(2, '0');
                               
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(function(row) {
                const timeCell = row.querySelector('td:first-child');
                if (timeCell) {
                    const timeText = timeCell.textContent.trim();
                    const timeParts = timeText.split(' - ');
                    if (timeParts.length === 2) {
                        const startTime = timeParts[0] + ':00';
                        const endTime = timeParts[1] + ':00';
                        
                        if (currentTime >= startTime && currentTime <= endTime) {
                            row.classList.add('bg-green-50');
                            const statusDiv = timeCell.querySelector('.text-xs');
                            if (!statusDiv) {
                                const newStatusDiv = document.createElement('div');
                                newStatusDiv.className = 'text-xs text-green-600 font-medium';
                                newStatusDiv.textContent = 'Sedang Berlangsung';
                                timeCell.querySelector('.ml-4').appendChild(newStatusDiv);
                            }
                        } else {
                            row.classList.remove('bg-green-50');
                            const statusDiv = timeCell.querySelector('.text-xs.text-green-600');
                            if (statusDiv) {
                                statusDiv.remove();
                            }
                        }
                    }
                }
            });
        }
        
        // Initial update and then every minute
        updateCurrentClass();
        setInterval(updateCurrentClass, 60000);
    });
</script>
@endpush
