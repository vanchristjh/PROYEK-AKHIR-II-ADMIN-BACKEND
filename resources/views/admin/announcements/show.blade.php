@extends('layouts.dashboard')

@php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
@endphp

@section('title', 'Detail Pengumuman')

@section('header', 'Detail Pengumuman')

@section('navigation')
    <li>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-tachometer-alt text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.users.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-users text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Pengguna</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.subjects.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-book text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Mata Pelajaran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.classrooms.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-school text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Kelas</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.announcements.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-3 text-white">
            <i class="fas fa-bullhorn text-lg w-6"></i>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
@endsection

@section('content')
    <!-- Enhanced header with animation and better styling -->
    <div class="bg-gradient-to-r from-red-500 to-amber-500 animate-gradient-x rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden transform transition hover:shadow-xl duration-300">
        <div class="absolute -right-10 -top-10 opacity-10 transform transition-transform duration-700 hover:scale-110">
            <i class="fas fa-bullhorn text-9xl"></i>
        </div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative z-10 animate-fade-in">
            <h2 class="text-2xl font-bold mb-2 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                Detail Pengumuman
            </h2>
            <p class="text-amber-100">Informasi lengkap tentang pengumuman yang dipilih</p>
        </div>
        
        <div class="mt-4 flex space-x-2 relative z-10">
            <a href="{{ route('admin.announcements.edit', $announcement) }}" class="px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg hover:bg-white/30 transition-all duration-300 flex items-center text-sm">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('admin.announcements.index') }}" class="px-4 py-2 bg-black/10 backdrop-blur-sm border border-white/20 rounded-lg hover:bg-black/20 transition-all duration-300 flex items-center text-sm">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 transition-all hover:shadow-md animate-fade-in">
        <!-- Announcement header with improved styling -->
        <div class="p-6 border-b {{ $announcement->is_important ? 'bg-gradient-to-r from-red-50 to-red-100 border-red-100' : 'bg-gradient-to-r from-gray-50 to-blue-50 border-gray-100' }}">
            <div class="flex items-start">
                <div class="flex-shrink-0 h-14 w-14 rounded-xl 
                    {{ $announcement->is_important ? 'bg-gradient-to-br from-red-400 to-red-600' : 'bg-gradient-to-br from-blue-400 to-blue-600' }} 
                    flex items-center justify-center text-white shadow-md {{ $announcement->is_important ? 'shadow-red-200' : 'shadow-blue-200' }} transform transition-all duration-300 hover:scale-110">
                    <i class="fas fa-{{ $announcement->is_important ? 'exclamation-circle' : 'bullhorn' }} text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <div class="flex items-center flex-wrap gap-2">
                        @if($announcement->is_important)
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> Penting
                            </span>
                        @endif
                        <h1 class="text-2xl font-bold text-gray-900 break-words">{{ $announcement->title }}</h1>
                    </div>
                    <div class="mt-3 flex flex-wrap items-center text-sm text-gray-500 gap-4">
                        <span class="flex items-center bg-gray-100/80 rounded-full px-3 py-1">
                            <i class="fas fa-user mr-2 text-gray-600"></i>
                            {{ $announcement->author->name }}
                        </span>
                        <span class="flex items-center bg-gray-100/80 rounded-full px-3 py-1">
                            <i class="fas fa-calendar mr-2 text-gray-600"></i>
                            {{ $announcement->publish_date->format('d M Y, H:i') }}
                        </span>
                        <span class="flex items-center bg-gray-100/80 rounded-full px-3 py-1">
                            <i class="fas fa-users mr-2 text-gray-600"></i>
                            {{ $announcement->audience == 'all' ? 'Semua' : ($announcement->audience == 'teachers' ? 'Guru' : 'Siswa') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Announcement content with improved typography -->
        <div class="p-6">
            <div class="prose max-w-none bg-gray-50/50 p-6 rounded-xl border border-gray-100 shadow-inner">
                {!! nl2br(e($announcement->content)) !!}
            </div>
            
            @if($announcement->attachment)
                <div class="mt-8 p-5 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200 transition-all hover:shadow-md">
                    <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-paperclip mr-2 text-gray-600"></i>
                        Lampiran
                    </h3>
                    <div class="flex flex-col md:flex-row md:items-center gap-4">
                        @php
                            $extension = pathinfo(storage_path('app/public/' . $announcement->attachment), PATHINFO_EXTENSION);
                            $icon = 'file';
                            $color = 'gray';
                            
                            if (in_array(strtolower($extension), ['pdf'])) {
                                $icon = 'file-pdf';
                                $color = 'red';
                            } elseif (in_array(strtolower($extension), ['doc', 'docx'])) {
                                $icon = 'file-word';
                                $color = 'blue';
                            } elseif (in_array(strtolower($extension), ['xls', 'xlsx'])) {
                                $icon = 'file-excel';
                                $color = 'green';
                            } elseif (in_array(strtolower($extension), ['ppt', 'pptx'])) {
                                $icon = 'file-powerpoint';
                                $color = 'orange';
                            } elseif (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])) {
                                $icon = 'file-image';
                                $color = 'purple';
                            } elseif (in_array(strtolower($extension), ['zip', 'rar'])) {
                                $icon = 'file-archive';
                                $color = 'yellow';
                            }
                        @endphp
                        
                        <div class="h-14 w-14 rounded-xl bg-gradient-to-br from-{{ $color }}-400 to-{{ $color }}-600 flex items-center justify-center text-white shadow-md shadow-{{ $color }}-200 transform transition-all duration-300 hover:scale-105">
                            <i class="fas fa-{{ $icon }} text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">{{ basename($announcement->attachment) }}</p>
                            <p class="text-sm text-gray-500">
                                {{ strtoupper($extension) }} File
                                @if(file_exists(storage_path('app/public/' . $announcement->attachment)))
                                    - {{ round(filesize(storage_path('app/public/' . $announcement->attachment)) / 1024, 2) }} KB
                                @endif
                            </p>
                        </div>
                        <div class="flex gap-2 mt-2 md:mt-0">
                            <a href="{{ route('admin.announcements.download', $announcement) }}" class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-lg hover:from-indigo-600 hover:to-indigo-700 transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center text-sm">
                                <i class="fas fa-download mr-2"></i> Download
                            </a>
                            
                            @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'pdf']))
                            <a href="{{ Storage::url($announcement->attachment) }}" target="_blank" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center text-sm">
                                <i class="fas fa-eye mr-2"></i> Lihat
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Announcement metadata with enhanced card display -->
            <div class="mt-8 border-t border-gray-200 pt-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100/50 rounded-xl p-5 shadow-sm transition-all hover:shadow-md border border-blue-100/50 transform hover:-translate-y-1 duration-300">
                        <h4 class="text-sm font-semibold text-blue-800 uppercase mb-3 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i> Status
                        </h4>
                        <div class="flex items-center">
                            @php
                                $now = now();
                                $isPublished = $announcement->publish_date <= $now;
                                $isExpired = $announcement->expiry_date && $announcement->expiry_date <= $now;
                                
                                $status = $isPublished ? ($isExpired ? 'expired' : 'active') : 'pending';
                                $statusText = $status == 'active' ? 'Aktif' : ($status == 'pending' ? 'Menunggu Publikasi' : 'Kedaluwarsa');
                                $statusColor = $status == 'active' ? 'green' : ($status == 'pending' ? 'yellow' : 'red');
                                $statusIcon = $status == 'active' ? 'check-circle' : ($status == 'pending' ? 'clock' : 'times-circle');
                            @endphp
                            
                            <div class="h-8 w-8 rounded-full flex items-center justify-center bg-{{ $statusColor }}-100 mr-3">
                                <i class="fas fa-{{ $statusIcon }} text-{{ $statusColor }}-500"></i>
                            </div>
                            <span class="font-medium text-gray-800">{{ $statusText }}</span>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100/50 rounded-xl p-5 shadow-sm transition-all hover:shadow-md border border-gray-100/50 transform hover:-translate-y-1 duration-300">
                        <h4 class="text-sm font-semibold text-gray-700 uppercase mb-3 flex items-center">
                            <i class="fas fa-calendar mr-2"></i> Periode
                        </h4>
                        <div class="text-gray-800 space-y-3">
                            <div class="flex items-center p-2 bg-white rounded-lg shadow-sm">
                                <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 mr-3">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <span>Publikasi: {{ $announcement->publish_date->format('d M Y, H:i') }}</span>
                            </div>
                            @if($announcement->expiry_date)
                                <div class="flex items-center p-2 bg-white rounded-lg shadow-sm">
                                    <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 mr-3">
                                        <i class="fas fa-calendar-times"></i>
                                    </div>
                                    <span>Kedaluwarsa: {{ $announcement->expiry_date->format('d M Y, H:i') }}</span>
                                </div>
                            @else
                                <div class="flex items-center p-2 bg-white rounded-lg shadow-sm">
                                    <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 mr-3">
                                        <i class="fas fa-infinity"></i>
                                    </div>
                                    <span>Tidak memiliki batas waktu</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer with actions and improved styling -->
        <div class="bg-gray-50 p-5 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-sm text-gray-500 space-y-1">
                <div class="flex items-center">
                    <i class="fas fa-history text-gray-400 mr-2"></i>
                    <span>Dibuat pada {{ $announcement->created_at->format('d M Y, H:i') }}</span>
                </div>
                @if($announcement->created_at != $announcement->updated_at)
                    <div class="flex items-center">
                        <i class="fas fa-edit text-gray-400 mr-2"></i>
                        <span>Diperbarui pada {{ $announcement->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                @endif
            </div>
            <div class="w-full md:w-auto">
                <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full md:w-auto px-5 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <i class="fas fa-trash"></i> Hapus Pengumuman
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Enhanced prose styling for better readability */
    .prose {
        color: #374151;
        max-width: 65ch;
        font-size: 1rem;
        line-height: 1.75;
    }
    
    .prose p {
        margin-top: 1.25em;
        margin-bottom: 1.25em;
    }
    
    .prose strong {
        font-weight: 600;
        color: #111827;
    }
    
    .prose a {
        color: #2563eb;
        text-decoration: underline;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .prose a:hover {
        color: #1d4ed8;
    }
    
    .prose h1, .prose h2, .prose h3, .prose h4 {
        color: #111827;
        font-weight: 600;
        line-height: 1.25;
        margin-top: 2em;
        margin-bottom: 1em;
    }
    
    .prose h1 {
        font-size: 2.25em;
        margin-top: 0;
    }
    
    .prose h2 {
        font-size: 1.5em;
    }
    
    .prose h3 {
        font-size: 1.25em;
    }
    
    .prose h4 {
        font-size: 1em;
    }
    
    .prose ul, .prose ol {
        margin-top: 1.25em;
        margin-bottom: 1.25em;
        padding-left: 1.625em;
    }
    
    .prose li {
        margin-top: 0.5em;
        margin-bottom: 0.5em;
    }
    
    /* Animation styles */
    .animate-gradient-x {
        background-size: 200% 200%;
        animation: gradient-x 15s ease infinite;
    }
    
    .animate-fade-in {
        animation: fade-in 0.6s ease-in-out;
    }
    
    @keyframes gradient-x {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
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
