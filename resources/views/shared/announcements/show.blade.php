@php
    // Determine the route prefix based on current route
    $prefix = '';
    if (request()->route()->getPrefix() === 'admin') {
        $prefix = 'admin';
    } elseif (request()->route()->getPrefix() === 'guru') {
        $prefix = 'guru';
    } else {
        $prefix = 'siswa';
    }
@endphp

@extends('layouts.dashboard')

@section('title', 'Detail Pengumuman')

@section('header', 'Detail Pengumuman')

@section('navigation')
    <!-- Set appropriate navigation links based on user role -->
    @if($prefix === 'admin')
        @include('admin.partials.navigation', ['active' => 'announcements'])
    @elseif($prefix === 'guru')
        @include('guru.partials.navigation', ['active' => 'announcements'])
    @else
        @include('siswa.partials.navigation', ['active' => 'announcements'])
    @endif
@endsection

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Detail Pengumuman</h2>
        <div class="flex space-x-3">
            <a href="{{ route($prefix.'.announcements.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            @if(auth()->user()->isAdmin() || auth()->id() === $announcement->author_id)
                <a href="{{ route($prefix.'.announcements.edit', $announcement) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <form action="{{ route($prefix.'.announcements.destroy', $announcement) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-trash mr-2"></i> Hapus
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <!-- Header -->
        <div class="p-6 {{ $announcement->is_important ? 'bg-red-50 border-b border-red-100' : 'border-b border-gray-100' }}">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 rounded-full {{ $announcement->is_important ? 'bg-red-100 text-red-600' : 'bg-indigo-100 text-indigo-600' }} flex items-center justify-center">
                        <i class="fas fa-{{ $announcement->is_important ? 'exclamation-circle' : 'bullhorn' }} text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            @if($announcement->is_important)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mr-2">
                                    Penting
                                </span>
                            @endif
                            {{ $announcement->title }}
                        </h3>
                        <div class="flex items-center text-sm text-gray-500 mt-1">
                            <span>Dibuat oleh: {{ $announcement->author->name }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $announcement->publish_date->format('d M Y, H:i') }}</span>
                            <span class="mx-2">•</span>
                            <span>Ditujukan untuk: 
                                <span class="font-medium">
                                    @switch($announcement->audience)
                                        @case('all')
                                            Semua
                                            @break
                                        @case('administrators')
                                            Admin
                                            @break
                                        @case('teachers')
                                            Guru
                                            @break
                                        @case('students')
                                            Siswa
                                            @break
                                    @endswitch
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                @if($announcement->updated_at->gt($announcement->created_at))
                    <span class="text-xs text-gray-500">
                        Terakhir diperbarui: {{ $announcement->updated_at->format('d M Y, H:i') }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="prose max-w-none">
                {!! $announcement->content !!}
            </div>
            
            <!-- Attachment if exists -->
            @if($announcement->attachment)
                <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <h4 class="text-base font-medium text-gray-900 mb-3">Lampiran</h4>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                            <i class="fas fa-paperclip"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            @php
                                $filename = basename($announcement->attachment);
                                $extension = pathinfo($announcement->attachment, PATHINFO_EXTENSION);
                                $icon = 'file-alt';
                                
                                if (in_array(strtolower($extension), ['pdf'])) {
                                    $icon = 'file-pdf';
                                } elseif (in_array(strtolower($extension), ['doc', 'docx'])) {
                                    $icon = 'file-word';
                                } elseif (in_array(strtolower($extension), ['xls', 'xlsx'])) {
                                    $icon = 'file-excel';
                                } elseif (in_array(strtolower($extension), ['ppt', 'pptx'])) {
                                    $icon = 'file-powerpoint';
                                } elseif (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])) {
                                    $icon = 'file-image';
                                }
                            @endphp
                            <div class="text-sm font-medium text-gray-900 flex items-center">
                                <i class="fas fa-{{ $icon }} mr-2 text-gray-500"></i>
                                {{ $filename }}
                            </div>
                            <div class="mt-1 flex items-center">
                                <a href="{{ route($prefix.'.announcements.download', $announcement) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-download mr-1"></i> Download
                                </a>
                                <span class="mx-2 text-gray-300">|</span>
                                <span class="text-xs text-gray-500">{{ strtoupper($extension) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Display actions or related information -->
    <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-600 border border-gray-200">
        <div class="flex items-center mb-2">
            <i class="fas fa-calendar-check text-indigo-500 mr-2"></i>
            <span>Dipublikasikan pada {{ $announcement->publish_date->format('d F Y, H:i') }}</span>
        </div>
        
        @if($announcement->expiry_date)
        <div class="flex items-center mb-2">
            <i class="fas fa-calendar-times text-amber-500 mr-2"></i>
            <span>Kedaluwarsa pada {{ $announcement->expiry_date->format('d F Y, H:i') }}</span>
        </div>
        @endif
        
        <div class="flex items-center">
            <i class="fas fa-users text-blue-500 mr-2"></i>
            <span>Dapat dilihat oleh 
                <span class="font-medium">
                    @switch($announcement->audience)
                        @case('all')
                            Semua Pengguna
                            @break
                        @case('administrators')
                            Administrator
                            @break
                        @case('teachers')
                            Guru
                            @break
                        @case('students')
                            Siswa
                            @break
                    @endswitch
                </span>
            </span>
        </div>
    </div>
@endsection
