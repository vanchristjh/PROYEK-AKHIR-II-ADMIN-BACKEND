@extends('layouts.dashboard')

@section('title', 'Detail Pengumpulan Tugas')

@section('header', 'Detail Pengumpulan Tugas')

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
        <a href="{{ route('siswa.schedule.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-blue-700/50 transition-all duration-200">
                <i class="fas fa-calendar-alt text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Jadwal Pelajaran</span>
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
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-green-700/50 transition-all duration-200">
                <i class="fas fa-book text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Materi</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.submissions.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-2.5 group relative text-white">
            <div class="p-1.5 rounded-lg bg-blue-800 transition-all duration-200">
                <i class="fas fa-file-upload text-lg w-5 h-5 flex items-center justify-center text-white"></i>
            </div>
            <span class="ml-3">Pengumpulan Tugas</span>
            <span class="absolute inset-y-0 left-0 w-1 bg-blue-400 rounded-tr-md rounded-br-md"></span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.grades.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-amber-700/50 transition-all duration-200">
                <i class="fas fa-star text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Nilai</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.attendance.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-purple-700/50 transition-all duration-200">
                <i class="fas fa-clipboard-check text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Kehadiran</span>
        </a>
    </li>
@endsection

@section('content')
    <!-- Back button -->
    <div class="mb-4">
        <a href="{{ route('siswa.submissions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-lg transition-colors duration-300">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Pengumpulan
        </a>
    </div>

    <!-- Header with animation -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-file-upload text-9xl"></i>
        </div>
        <div class="relative z-10">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold mb-1">{{ $submission->assignment->title }}</h2>
                    <p class="text-blue-100">Pengumpulan Tugas</p>
                </div>
                
                @if($submission->score !== null)
                    <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-sm rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold">{{ $submission->score }}</div>
                        <div class="text-xs text-blue-100">/ {{ $submission->assignment->max_score }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Submission details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Detail Pengumpulan
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Waktu Pengumpulan</label>
                            <div class="text-gray-900">
                                {{ \Carbon\Carbon::parse($submission->submitted_at)->format('d M Y, H:i') }}
                                @if($submission->submitted_at > $submission->assignment->deadline)
                                    <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                        Terlambat
                                    </span>
                                @else
                                    <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                        Tepat Waktu
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        @if($submission->file_path)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">File Tugas</label>
                                <div class="flex items-center p-3 border border-gray-200 rounded-lg bg-gray-50">
                                    <div class="mr-3 bg-blue-100 text-blue-600 p-2 rounded-md">
                                        <i class="fas fa-file-alt text-lg"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <div class="text-sm font-medium text-gray-900 truncate">
                                            {{ basename($submission->file_path) }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Diunggah pada {{ \Carbon\Carbon::parse($submission->created_at)->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                    <a href="{{ route('siswa.submissions.download', $submission) }}" class="ml-auto bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1.5 rounded-md shadow-sm transition-colors duration-300">
                                        <i class="fas fa-download mr-1"></i> Unduh
                                    </a>
                                </div>
                            </div>
                        @endif
                        
                        @if($submission->notes)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Catatan</label>
                                <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                    <p class="text-gray-800 whitespace-pre-line">{{ $submission->notes }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($submission->score !== null)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Nilai</label>
                                <div class="flex items-center">
                                    <span class="text-2xl font-bold text-gray-900">{{ $submission->score }}</span>
                                    <span class="text-gray-500 ml-1">/ {{ $submission->assignment->max_score }}</span>
                                </div>
                            </div>
                            
                            @if($submission->feedback)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Feedback Guru</label>
                                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                        <p class="text-gray-800 whitespace-pre-line">{{ $submission->feedback }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Status Penilaian</label>
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>Dinilai pada {{ \Carbon\Carbon::parse($submission->graded_at)->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Assignment details -->
        <div>
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-tasks text-green-500 mr-2"></i>
                        Detail Tugas
                    </h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Mata Pelajaran</label>
                            <div class="text-sm text-gray-900">{{ $submission->assignment->subject->name }}</div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Kelas</label>
                            <div class="text-sm text-gray-900">{{ $submission->assignment->classroom->name }}</div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Deadline</label>
                            <div class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($submission->assignment->deadline)->format('d M Y, H:i') }}
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Nilai Maksimal</label>
                            <div class="text-sm text-gray-900">{{ $submission->assignment->max_score }}</div>
                        </div>
                        
                        <div class="pt-2">
                            <a href="{{ route('siswa.assignments.show', $submission->assignment_id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-eye mr-2"></i>
                                Lihat Detail Tugas
                            </a>
                        </div>
                    </div>
                </div>
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
</style>
@endpush
