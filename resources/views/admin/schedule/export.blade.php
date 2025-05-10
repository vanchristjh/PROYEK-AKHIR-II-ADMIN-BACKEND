@extends('layouts.dashboard')

@section('title', 'Ekspor Jadwal Kelas ' . $classroom->name)

@section('header', 'Ekspor Jadwal Kelas ' . $classroom->name)

@section('navigation')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <!-- Header with animation -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-file-export text-9xl"></i>
        </div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Jadwal Kelas {{ $classroom->name }}</h2>
            <p class="text-blue-100">Tahun Ajaran {{ $classroom->academic_year }}</p>
        </div>
    </div>

    <div class="mb-6">
        <a href="{{ route('admin.schedule.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
            <i class="fas fa-chevron-left mr-2 text-sm"></i>
            <span>Kembali ke Daftar Jadwal</span>
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 mb-6">
        <div class="px-5 py-4 bg-gray-50 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    <h3 class="font-medium text-gray-700">Informasi Kelas</h3>
                </div>
                <div class="flex space-x-2">
                    <button id="printScheduleBtn" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center">
                        <i class="fas fa-print mr-1.5"></i> Cetak
                    </button>
                    <button id="shareLinkBtn" class="px-3 py-1.5 text-sm bg-green-600 text-white rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors flex items-center">
                        <i class="fas fa-share-alt mr-1.5"></i> Bagikan Link
                    </button>
                </div>
            </div>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-chalkboard text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Nama Kelas</p>
                                <p class="text-lg font-medium text-gray-900">{{ $classroom->name }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-school text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Tahun Ajaran</p>
                                <p class="text-lg font-medium text-gray-900">{{ $classroom->academic_year }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-users text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Jumlah Murid</p>
                                <p class="text-lg font-medium text-gray-900">{{ $classroom->students_count ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-calendar-check text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Jumlah Mata Pelajaran</p>
                                <p class="text-lg font-medium text-gray-900">{{ count($schedulesByDay) > 0 ? count(array_reduce(array_values($schedulesByDay), 'array_merge', [])) : 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Schedule -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <div class="px-5 py-4 bg-gray-50 border-b border-gray-100">
            <div class="flex items-center">
                <i class="fas fa-calendar-week text-gray-500 mr-2"></i>
                <h3 class="font-medium text-gray-700">Jadwal Mingguan</h3>
            </div>
        </div>

        <div class="p-5">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                @foreach([1, 2, 3, 4, 5] as $day)
                    <div class="bg-gray-50 rounded-lg overflow-hidden border border-gray-100">
                        <div class="p-3 @if($day == 1) bg-blue-100 @elseif($day == 2) bg-indigo-100 @elseif($day == 3) bg-purple-100 @elseif($day == 4) bg-pink-100 @else bg-red-100 @endif">
                            <h4 class="font-semibold text-center">{{ $dayNames[$day] }}</h4>
                        </div>
                        <div class="p-4">
                            @if(isset($schedulesByDay[$day]) && count($schedulesByDay[$day]) > 0)
                                <div class="space-y-3">
                                    @foreach($schedulesByDay[$day] as $schedule)
                                        <div class="p-3 bg-white rounded-lg border border-gray-100 hover:shadow-md transition-shadow">
                                            <div class="font-medium text-gray-900">{{ $schedule->subject->name }}</div>
                                            <div class="text-sm text-gray-700 flex items-center mt-1">
                                                <i class="fas fa-clock text-gray-400 mr-1.5 w-4"></i>
                                                {{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}
                                            </div>
                                            <div class="text-sm text-gray-700 flex items-center mt-1">
                                                <i class="fas fa-user text-gray-400 mr-1.5 w-4"></i>
                                                {{ $schedule->teacher->name }}
                                            </div>
                                            @if($schedule->room)
                                                <div class="text-sm text-gray-700 flex items-center mt-1">
                                                    <i class="fas fa-door-open text-gray-400 mr-1.5 w-4"></i>
                                                    {{ $schedule->room }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div class="text-gray-400 mb-2">
                                        <i class="fas fa-calendar-times text-2xl"></i>
                                    </div>
                                    <p class="text-sm text-gray-500">Tidak ada jadwal</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
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
        .no-print {
            display: none !important;
        }
        
        body {
            background: white;
        }
        
        .print-full-width {
            width: 100% !important;
            max-width: 100% !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const printBtn = document.getElementById('printScheduleBtn');
        const shareLinkBtn = document.getElementById('shareLinkBtn');
        
        // Print schedule
        printBtn.addEventListener('click', function() {
            window.print();
        });
        
        // Share link
        shareLinkBtn.addEventListener('click', function() {
            // Create a temporary input to copy the URL
            const tempInput = document.createElement('input');
            tempInput.value = window.location.href;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            
            // Notify the user
            alert('Link jadwal berhasil disalin ke clipboard!');
        });
    });
</script>
@endpush
