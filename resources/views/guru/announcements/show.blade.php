@extends('layouts.dashboard')

@section('title', $announcement->title)

@section('header', 'Detail Pengumuman')

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
        <a href="{{ route('guru.announcements.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-3 text-white">
            <i class="fas fa-bullhorn text-lg w-6"></i>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-2">
            <a href="{{ route('guru.announcements.index') }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <span class="text-gray-500">/</span>
            <h2 class="text-xl font-semibold text-gray-800">Detail Pengumuman</h2>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('guru.announcements.edit', $announcement) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <form action="{{ route('guru.announcements.destroy', $announcement) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash-alt mr-2"></i> Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                        {{ $announcement->title }}
                        @if($announcement->is_important)
                            <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                                <i class="fas fa-exclamation-circle mr-1"></i> Penting
                            </span>
                        @endif
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Dipublikasikan {{ $announcement->publish_date->format('d M Y, H:i') }}
                    </p>
                </div>
                <div class="text-right text-sm text-gray-500">
                    <p>Untuk: 
                        @if($announcement->audience === 'all')
                            <span class="font-medium">Semua</span>
                        @elseif($announcement->audience === 'teachers')
                            <span class="font-medium">Guru</span>
                        @elseif($announcement->audience === 'students')
                            <span class="font-medium">Siswa</span>
                        @endif
                    </p>
                    @if($announcement->expiry_date)
                        <p class="mt-1">Kedaluwarsa: {{ $announcement->expiry_date->format('d M Y, H:i') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="prose max-w-none">
                {!! nl2br(e($announcement->content)) !!}
            </div>

            @if($announcement->attachment)
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Lampiran</h4>
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="w-10 h-10 flex-shrink-0 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                            @php
                                $ext = pathinfo($announcement->attachment, PATHINFO_EXTENSION);
                                $iconClass = 'fa-file';
                                
                                if (in_array($ext, ['pdf'])) {
                                    $iconClass = 'fa-file-pdf text-red-500';
                                } elseif (in_array($ext, ['doc', 'docx'])) {
                                    $iconClass = 'fa-file-word text-blue-500';
                                } elseif (in_array($ext, ['xls', 'xlsx'])) {
                                    $iconClass = 'fa-file-excel text-green-500';
                                } elseif (in_array($ext, ['ppt', 'pptx'])) {
                                    $iconClass = 'fa-file-powerpoint text-orange-500';
                                } elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                                    $iconClass = 'fa-file-image text-purple-500';
                                } elseif (in_array($ext, ['zip', 'rar'])) {
                                    $iconClass = 'fa-file-archive text-yellow-500';
                                }
                            @endphp
                            <i class="fas {{ $iconClass }} text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h5 class="text-sm font-medium text-gray-900">{{ basename($announcement->attachment) }}</h5>
                            <a href="{{ Storage::url($announcement->attachment) }}" target="_blank" class="text-xs text-indigo-600 hover:text-indigo-900 mt-1 inline-flex items-center">
                                <i class="fas fa-download mr-1"></i> Unduh Lampiran
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
