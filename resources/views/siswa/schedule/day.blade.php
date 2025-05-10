@extends('layouts.dashboard')

@section('title', 'Jadwal Hari ' . $dayName)

@section('header', 'Jadwal Hari ' . $dayName)

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
                                    @endphp
                                    <tr class="{{ $isCurrentClass ? 'bg-green-50' : 'hover:bg-gray-50' }} transition-colors duration-200">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8 rounded-full {{ $isCurrentClass ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }} flex items-center justify-center">
                                                    <i class="{{ $isCurrentClass ? 'fas fa-play-circle' : 'fas fa-clock' }}"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $isCurrentClass ? 'Sedang berlangsung' : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $schedule->subject->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $schedule->subject->code ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $schedule->teacher->name }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $schedule->room ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $schedule->room ?? 'Belum ditentukan' }}
                                            </span>
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
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">Jumlah Mata Pelajaran</p>
                                    <p class="text-lg font-medium text-gray-900">{{ count($schedules) }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">Mulai - Selesai</p>
                                    <p class="text-lg font-medium text-gray-900">
                                        @if(count($schedules) > 0)
                                            {{ date('H:i', strtotime($schedules->first()->start_time)) }} - 
                                            {{ date('H:i', strtotime($schedules->last()->end_time)) }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">Guru</p>
                                    <div class="text-gray-900">
                                        @foreach($schedules as $schedule)
                                            <p class="text-sm">{{ $schedule->teacher->name }} <span class="text-xs text-gray-500">({{ $schedule->subject->name }})</span></p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <button onclick="window.print()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:bg-blue-700 transition-colors flex items-center justify-center">
                                <i class="fas fa-print mr-2"></i> Cetak Jadwal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                <div class="mx-auto w-16 h-16 mb-4 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                    <i class="fas fa-calendar-times text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-800 mb-2">Tidak Ada Jadwal</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-6">Tidak ada jadwal pelajaran yang ditetapkan untuk hari {{ $dayName }}.</p>
                <a href="{{ route('siswa.schedule.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Jadwal Mingguan
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
        0% { opacity: 0; transform: translateY(10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    
    @media print {
        header, .sidebar, footer, .no-print, button {
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
