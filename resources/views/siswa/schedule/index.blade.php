@extends('layouts.dashboard')

@section('title', 'Jadwal Pelajaran')

@section('header', 'Jadwal Pelajaran')

@section('navigation')
    <li>
        <a href="{{ route('siswa.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-indigo-700 transition-all duration-200">
                <i class="fas fa-tachometer-alt text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.schedule.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-2.5 group relative text-white">
            <div class="p-1.5 rounded-lg bg-blue-800 transition-all duration-200">
                <i class="fas fa-calendar-alt text-lg w-5 h-5 flex items-center justify-center text-white"></i>
            </div>
            <span class="ml-3">Jadwal Pelajaran</span>
            <span class="absolute inset-y-0 left-0 w-1 bg-blue-400 rounded-tr-md rounded-br-md"></span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.assignments.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-blue-700/50 transition-all duration-200">
                <i class="fas fa-tasks text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Tugas</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.materials.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-blue-700/50 transition-all duration-200">
                <i class="fas fa-book text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Materi Pelajaran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.grades.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-blue-700/50 transition-all duration-200">
                <i class="fas fa-star text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Nilai</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.announcements.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-red-700/50 transition-all duration-200">
                <i class="fas fa-bullhorn text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
@endsection

@section('content')
    <!-- Enhanced Header with animation -->
    <div class="bg-gradient-to-r from-blue-500 to-cyan-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-calendar-alt text-9xl"></i>
        </div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute right-1/3 -top-12 w-36 h-36 bg-blue-300/20 rounded-full blur-3xl"></div>
        <div class="relative z-10">
            <div class="flex items-center">
                <div class="bg-white/20 p-2 rounded-lg shadow-inner backdrop-blur-sm mr-4">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold mb-1">Jadwal Pelajaran</h2>                    <p class="text-blue-100">Lihat jadwal kelas Anda untuk minggu ini</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('siswa.schedule.export-ical') }}" class="btn-glass flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300">
                    <i class="fas fa-calendar-plus mr-2"></i> Ekspor ke Kalender
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 transform transition-all duration-300 hover:shadow-md">
        <div class="flex items-center space-x-4 mb-6">
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-800">Jadwal Mingguan</h2>
        </div>
        
        @if(isset($message))
            <div class="p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 rounded-lg animate-fade-in">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-3 text-yellow-500"></i>
                    <p>{{ $message }}</p>
                </div>
            </div>
        @else
            <div class="overflow-x-auto scrollbar-hide">
                <div class="grid grid-cols-1 lg:grid-cols-7 gap-4 min-w-max">
                    @foreach($schedulesByDay as $day => $daySchedules)
                        @php 
                            $isToday = date('N') == $day;
                            $bgClass = $isToday ? 'bg-blue-50 ring-2 ring-blue-300' : 'bg-gray-50';
                        @endphp                        <div class="{{ $bgClass }} rounded-xl shadow-sm p-4 transition-transform duration-300 hover:scale-[1.02]">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-lg font-medium {{ $isToday ? 'text-blue-800' : 'text-gray-800' }}">
                                    @if($isToday)
                                        <span class="inline-block bg-blue-500 text-white text-xs px-2 py-1 rounded-full mr-2">Hari Ini</span>
                                    @endif
                                    {{ $dayNames[$day] }}
                                </h3>
                                <a href="{{ route('siswa.schedule.day', $day) }}" class="text-xs text-blue-600 hover:text-blue-800 hover:underline" title="Lihat detail">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                            
                            @if(count($daySchedules) > 0)                                <div class="space-y-3">
                                    @foreach($daySchedules as $schedule)
                                        <a href="{{ route('siswa.schedule.day', $day) }}" class="block">
                                            <div class="bg-white rounded-lg p-3 shadow-sm border border-gray-100 transform transition-all duration-200 hover:shadow-md hover:border-blue-200 hover:translate-x-1">
                                                <div class="font-medium text-gray-800">{{ $schedule->subject->name }}</div>
                                                <div class="flex items-center text-sm text-gray-500 mt-2">
                                                    <i class="fas fa-clock text-blue-500 mr-2"></i>
                                                    {{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}
                                                </div>
                                                <div class="text-sm text-gray-600 mt-3">
                                                    <div class="flex items-center mb-2">
                                                        <i class="fas fa-user-tie text-blue-600 mr-2"></i>
                                                        <span class="truncate">{{ $schedule->teacher->name }}</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                                                        <span class="truncate">{{ $schedule->room ?? 'Belum ditentukan' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-gray-500 py-8 border border-dashed border-gray-300 rounded-lg">
                                    <i class="fas fa-coffee text-gray-400 text-2xl mb-2"></i>
                                    <p>Tidak ada kelas dijadwalkan</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
    
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    
    @keyframes fadeIn {
        0% { opacity: 0; transform: translateY(10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush
