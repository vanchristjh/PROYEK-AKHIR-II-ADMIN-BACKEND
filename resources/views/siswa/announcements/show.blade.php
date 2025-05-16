@extends('layouts.dashboard')

@section('title', $announcement->title)

@section('header', 'Detail Pengumuman')

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
    @if(Route::has('siswa.attendance.index'))
    <li>
        <a href="{{ route('siswa.attendance.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-clipboard-check text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Kehadiran</span>
        </a>
    </li>
    @endif
    <li>
        <a href="{{ route('siswa.announcements.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-3 text-white">
            <i class="fas fa-bullhorn text-lg w-6"></i>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('siswa.announcements.index') }}" class="text-indigo-600 hover:text-indigo-800 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 mb-6">
        <div class="p-6">
            <div class="flex items-center mb-4">
                @if($announcement->is_important)
                    <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                        <i class="fas fa-exclamation-circle mr-1"></i> Penting
                    </span>
                @endif
                <h2 class="text-2xl font-bold text-gray-900">{{ $announcement->title }}</h2>
            </div>
            
            <div class="flex flex-wrap text-sm text-gray-500 gap-x-4 gap-y-2 mb-6 pb-4 border-b border-gray-100">
                <div class="flex items-center">
                    <i class="fas fa-user-circle mr-1"></i>
                    {{ $announcement->author->name ?? 'Unknown' }}
                </div>
                <div class="flex items-center">
                    <i class="fas fa-calendar mr-1"></i>
                    {{ $announcement->publish_date->format('d M Y') }}
                </div>
                <div class="flex items-center">
                    <i class="fas fa-users mr-1"></i>
                    <span>
                        @switch($announcement->audience)
                            @case('all')
                                Semua
                                @break
                            @case('teachers')
                                Guru
                                @break
                            @case('students')
                                Siswa
                                @break
                        @endswitch
                    </span>
                </div>
            </div>
            
            <div class="prose max-w-none mb-6">
                {!! nl2br(e($announcement->content)) !!}
            </div>
            
            @if($announcement->attachment)
                <div class="mt-6 bg-gray-50 p-4 rounded-lg border border-gray-200 flex items-center">
                    <div class="mr-4 text-indigo-500">
                        <i class="fas fa-paperclip text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-1">Lampiran:</h4>
                        <a href="{{ route('siswa.announcements.download', $announcement) }}" class="text-indigo-600 hover:text-indigo-800 flex items-center">
                            <span>{{ basename($announcement->attachment) }}</span>
                            <i class="fas fa-download ml-2"></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
