@extends('layouts.dashboard')

@section('title', 'Jadwal Mengajar')

@section('header', 'Jadwal Mengajar')

@section('navigation')
    <li>
        <a href="{{ route('guru.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-tachometer-alt text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.materials.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-book text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Materi Pelajaran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.assignments.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-tasks text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Tugas</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.grades.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-star text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Penilaian</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.attendance.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-clipboard-check text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Kehadiran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.schedule.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-3 text-white">
            <i class="fas fa-calendar-alt text-lg w-6"></i>
            <span class="ml-3">Jadwal Mengajar</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.announcements.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-bullhorn text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
@endsection

@section('content')
    <!-- Header with animation -->
    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-calendar-alt text-9xl"></i>
        </div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Jadwal Mengajar</h2>
            <p class="text-indigo-100">Kelola jadwal mengajar Anda untuk semua kelas.</p>
        </div>
    </div>

    <!-- Weekly schedule view -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 mb-6">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                    <i class="fas fa-calendar-week text-indigo-600"></i>
                </div>
                <span>Jadwal Mingguan</span>
            </h3>
            
            <div class="overflow-x-auto scrollbar-hide">
                <div class="min-w-max">
                    <!-- Days of week headers -->
                    <div class="grid grid-cols-6 gap-4 mb-4">
                        <div class="font-medium text-gray-700 text-center py-2">Senin</div>
                        <div class="font-medium text-gray-700 text-center py-2">Selasa</div>
                        <div class="font-medium text-gray-700 text-center py-2">Rabu</div>
                        <div class="font-medium text-gray-700 text-center py-2">Kamis</div>
                        <div class="font-medium text-gray-700 text-center py-2">Jumat</div>
                        <div class="font-medium text-gray-700 text-center py-2">Sabtu</div>
                    </div>
                    
                    <!-- Schedule grid -->
                    <div class="grid grid-cols-6 gap-4">
                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                            <div class="border rounded-lg bg-gray-50 p-2 min-h-[200px]">
                                @if(isset($schedulesByDay[$day]) && count($schedulesByDay[$day]) > 0)
                                    <div class="space-y-2">
                                        @foreach($schedulesByDay[$day] as $schedule)
                                            <div class="bg-white p-3 rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-all">
                                                <div class="font-medium text-indigo-600">{{ $schedule->subject->name }}</div>
                                                <div class="text-xs text-gray-500 mt-1 flex items-center">
                                                    <i class="fas fa-clock mr-1 text-gray-400"></i>
                                                    <span>{{ $schedule->formatted_time }}</span>
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1 flex items-center">
                                                    <i class="fas fa-users mr-1 text-gray-400"></i>
                                                    <span>{{ $schedule->classroom->name }}</span>
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1 flex items-center">
                                                    <i class="fas fa-door-open mr-1 text-gray-400"></i>
                                                    <span class="truncate">{{ $schedule->room ?? 'Tidak Ada Ruangan' }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center text-gray-500 py-8 border border-dashed border-gray-300 rounded-lg">
                                        <i class="fas fa-coffee text-gray-400 text-2xl mb-2"></i>
                                        <p>Tidak ada jadwal mengajar</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
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
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection