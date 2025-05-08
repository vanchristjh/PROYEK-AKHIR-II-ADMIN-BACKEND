@extends('layouts.dashboard')

@section('title', 'Jadwal Mengajar')

@section('header', 'Jadwal Mengajar')

@section('navigation')
    @include('guru.partials.sidebar')
@endsection

@section('content')
    <!-- Header with animation -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-calendar-alt text-9xl"></i>
        </div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute right-1/3 -top-12 w-36 h-36 bg-blue-300/20 rounded-full blur-3xl"></div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Jadwal Mengajar</h2>
            <p class="text-blue-100">Jadwal mengajar Anda untuk semua mata pelajaran dan kelas</p>
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

    <!-- Current Day Highlight -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6 border border-blue-100/50">
        <div class="p-5 bg-blue-50 border-b border-blue-100">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                    <i class="fas fa-calendar-day text-blue-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Jadwal Hari Ini</h3>
            </div>
        </div>
        <div class="p-6">
            @php
                $today = now()->dayOfWeek;
                $indonesianDays = [
                    0 => 'Minggu',
                    1 => 'Senin',
                    2 => 'Selasa',
                    3 => 'Rabu',
                    4 => 'Kamis',
                    5 => 'Jumat',
                    6 => 'Sabtu'
                ];
                $todayInIndonesian = $indonesianDays[$today];
                $currentTime = now()->format('H:i');
            @endphp
            
            <div class="text-center mb-4">
                <h4 class="text-xl font-bold text-blue-600">{{ $todayInIndonesian }}, {{ now()->format('d F Y') }}</h4>
                <p class="text-gray-500">Jam {{ now()->format('H:i') }}</p>
            </div>

            @if(isset($schedulesByDay[$todayInIndonesian]) && count($schedulesByDay[$todayInIndonesian]) > 0)
                <div class="space-y-3">
                    @foreach($schedulesByDay[$todayInIndonesian] as $schedule)
                        @php
                            $isOngoing = $currentTime >= $schedule->start_time && $currentTime <= $schedule->end_time;
                            $isUpcoming = $currentTime < $schedule->start_time;
                            $isPast = $currentTime > $schedule->end_time;
                            
                            $statusClass = $isOngoing ? 'bg-green-100 border-green-200 text-green-800' 
                                : ($isUpcoming ? 'bg-blue-100 border-blue-200 text-blue-800' 
                                : 'bg-gray-100 border-gray-200 text-gray-800');
                        @endphp
                        
                        <div class="flex items-center p-4 rounded-lg border {{ $statusClass }} transition-colors duration-300">
                            <div class="flex-shrink-0 p-3 rounded-full {{ $isOngoing ? 'bg-green-200 text-green-700' : ($isUpcoming ? 'bg-blue-200 text-blue-700' : 'bg-gray-200 text-gray-700') }}">
                                <i class="fas {{ $isOngoing ? 'fa-play-circle' : ($isUpcoming ? 'fa-clock' : 'fa-check-circle') }} text-lg"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-base font-medium text-gray-900">{{ $schedule->subject->name }}</h4>
                                <div class="flex items-center text-xs text-gray-500 mt-0.5">
                                    <span>{{ $schedule->classroom->name }}</span>
                                    <span class="mx-1">•</span>
                                    <span>{{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $isOngoing ? 'bg-green-100 text-green-800' : ($isUpcoming ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $isOngoing ? 'Sedang Berlangsung' : ($isUpcoming ? 'Akan Datang' : 'Selesai') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-8 flex flex-col items-center justify-center text-center">
                    <div class="mb-4 p-3 rounded-full bg-blue-100 text-blue-500">
                        <i class="fas fa-coffee text-2xl"></i>
                    </div>
                    <h5 class="text-lg font-medium text-gray-800 mb-1">Tidak Ada Jadwal Hari Ini</h5>
                    <p class="text-gray-500 max-w-md">Anda tidak memiliki jadwal mengajar untuk hari ini. Nikmati waktu Anda!</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Full Weekly Schedule -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
        <div class="p-5 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-blue-100/50">
            <div class="flex items-center">
                <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                    <i class="fas fa-calendar-week text-indigo-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Jadwal Mingguan</h3>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <div class="p-6">
                @if(isset($schedulesByDay) && count($schedulesByDay) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                            <div class="border border-gray-200 rounded-lg overflow-hidden {{ $day === $todayInIndonesian ? 'ring-2 ring-blue-500 ring-offset-2' : '' }}">
                                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 {{ $day === $todayInIndonesian ? 'bg-blue-50' : '' }}">
                                    <h3 class="font-semibold text-gray-800 {{ $day === $todayInIndonesian ? 'text-blue-700' : '' }}">{{ $day }}</h3>
                                </div>
                                
                                <div class="divide-y divide-gray-100">
                                    @if(isset($schedulesByDay[$day]) && count($schedulesByDay[$day]) > 0)
                                        @foreach($schedulesByDay[$day] as $schedule)
                                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                                                            <i class="fas fa-book"></i>
                                                        </div>
                                                        <div class="ml-4">
                                                            <p class="text-sm font-medium text-gray-900">{{ $schedule->subject->name }}</p>
                                                            <p class="text-xs text-gray-500">{{ $schedule->classroom->name }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="p-4 text-center text-gray-500 italic">
                                            Tidak ada jadwal
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="mx-auto w-16 h-16 mb-4 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                            <i class="fas fa-calendar-times text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">Belum Ada Jadwal</h3>
                        <p class="text-gray-500 max-w-md mx-auto">Anda belum memiliki jadwal mengajar. Silakan hubungi administrator untuk mendapatkan jadwal mengajar Anda.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        @php
            $totalSchedules = 0;
            $totalClasses = [];
            $totalSubjects = [];
            
            foreach ($schedulesByDay ?? [] as $daySchedules) {
                $totalSchedules += count($daySchedules);
                
                foreach ($daySchedules as $schedule) {
                    $totalClasses[$schedule->classroom->id] = $schedule->classroom->name;
                    $totalSubjects[$schedule->subject->id] = $schedule->subject->name;
                }
            }
        @endphp
        
        <div class="bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-sm p-6 border border-blue-100/20">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 rounded-lg bg-blue-100 text-blue-600">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-500">Total Jadwal</h4>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalSchedules }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-white to-green-50 rounded-xl shadow-sm p-6 border border-green-100/20">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 rounded-lg bg-green-100 text-green-600">
                    <i class="fas fa-chalkboard"></i>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-500">Kelas yang Diajar</h4>
                    <p class="text-2xl font-bold text-gray-800">{{ count($totalClasses) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-white to-purple-50 rounded-xl shadow-sm p-6 border border-purple-100/20">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 rounded-lg bg-purple-100 text-purple-600">
                    <i class="fas fa-book"></i>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-500">Mata Pelajaran</h4>
                    <p class="text-2xl font-bold text-gray-800">{{ count($totalSubjects) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
        <div class="p-5 bg-gray-50 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-file-export mr-2 text-indigo-500"></i> Opsi Ekspor
            </h3>
        </div>
        <div class="p-6">
            <p class="text-gray-600 mb-4">Unduh atau cetak jadwal mengajar untuk referensi offline Anda.</p>
            <div class="flex flex-wrap gap-3">
                <a href="#" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors inline-flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i> Ekspor PDF
                </a>
                <a href="#" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors inline-flex items-center">
                    <i class="fas fa-file-excel mr-2"></i> Ekspor Excel
                </a>
                <button class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors inline-flex items-center" onclick="window.print()">
                    <i class="fas fa-print mr-2"></i> Cetak
                </button>
            </div>
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

    @media print {
        header, .sidebar, footer, .no-print {
            display: none !important;
        }
        
        body {
            background-color: white;
        }
        
        main {
            margin: 0;
            padding: 0;
        }
        
        .shadow-sm, .shadow-md, .shadow-lg, .shadow-xl {
            box-shadow: none !important;
        }
        
        .rounded-xl, .rounded-lg {
            border-radius: 0 !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Code to highlight current class in schedule
        function updateCurrentSchedule() {
            const now = new Date();
            const currentTime = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
            
            document.querySelectorAll('.schedule-item').forEach(item => {
                const startTime = item.dataset.startTime;
                const endTime = item.dataset.endTime;
                
                if (currentTime >= startTime && currentTime <= endTime) {
                    item.classList.add('current-class');
                } else {
                    item.classList.remove('current-class');
                }
            });
        }
        
        // Initial call and set interval to update every minute
        updateCurrentSchedule();
        setInterval(updateCurrentSchedule, 60000);
    });
</script>
@endpush