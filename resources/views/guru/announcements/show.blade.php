@extends('layouts.dashboard')

@section('title', $announcement->title)

@section('header', 'Detail Pengumuman')

@section('navigation')
    @include('guru.partials.sidebar')
@endsection

@section('content')
    <div class="mb-6">
        <a href="{{ route('guru.announcements.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition-colors">
            <i class="fas fa-chevron-left mr-2 text-sm"></i>
            <span>Kembali ke Daftar Pengumuman</span>
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50 mb-6">
        <div class="p-6 border-b border-gray-100 {{ $announcement->is_important ? 'bg-gradient-to-r from-red-50 to-white' : 'bg-gradient-to-r from-blue-50 to-white' }}">
            <div class="flex items-start justify-between">
                <div class="flex items-start">
                    <div class="{{ $announcement->is_important ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }} p-3 rounded-full mr-4">
                        <i class="fas {{ $announcement->is_important ? 'fa-exclamation-circle' : 'fa-bullhorn' }} text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                            {{ $announcement->title }}
                            @if($announcement->is_important)
                                <span class="ml-3 px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Penting</span>
                            @endif
                        </h1>
                        <div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-day mr-1"></i>
                                {{ $announcement->publish_date->format('d M Y') }}
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-user mr-1"></i>
                                {{ $announcement->author->name ?? 'Unknown' }}
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-users mr-1"></i>
                                @if($announcement->audience === 'all')
                                    Semua Pengguna
                                @elseif($announcement->audience === 'students')
                                    Siswa
                                @elseif($announcement->audience === 'teachers')
                                    Guru
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($announcement->author_id == auth()->id())
                <div class="flex space-x-2">
                    <a href="{{ route('guru.announcements.edit', $announcement) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors flex items-center gap-1">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('guru.announcements.destroy', $announcement) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors flex items-center gap-1">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        
        <div class="p-6">
            <div class="prose max-w-none">
                {!! nl2br(e($announcement->content)) !!}
            </div>
            
            @if($announcement->attachment)
            <div class="mt-8 pt-6 border-t border-gray-100">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Lampiran</h3>
                <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                        @php
                            $extension = pathinfo(storage_path('app/public/' . $announcement->attachment), PATHINFO_EXTENSION);
                            $iconClass = 'fa-file';
                            
                            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg'])) {
                                $iconClass = 'fa-file-image';
                            } elseif (in_array($extension, ['pdf'])) {
                                $iconClass = 'fa-file-pdf';
                            } elseif (in_array($extension, ['doc', 'docx'])) {
                                $iconClass = 'fa-file-word';
                            } elseif (in_array($extension, ['xls', 'xlsx'])) {
                                $iconClass = 'fa-file-excel';
                            } elseif (in_array($extension, ['ppt', 'pptx'])) {
                                $iconClass = 'fa-file-powerpoint';
                            }
                        @endphp
                        <i class="fas {{ $iconClass }} text-indigo-600"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ basename($announcement->attachment) }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ strtoupper($extension) }} File
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ asset('storage/' . $announcement->attachment) }}" class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200 transition-colors inline-flex items-center" target="_blank">
                            <i class="fas fa-download mr-1"></i>
                            <span>Unduh</span>
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 text-sm text-gray-600">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
            <p>Pengumuman ini dipublikasikan pada {{ $announcement->publish_date->format('d F Y') }} dan terakhir diperbarui pada {{ $announcement->updated_at->format('d F Y H:i') }}</p>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .prose {
        line-height: 1.75;
    }
    
    .prose p {
        margin-top: 1.25em;
        margin-bottom: 1.25em;
    }
    
    .prose strong {
        font-weight: 600;
    }
    
    .prose ul {
        list-style-type: disc;
        margin-top: 1.25em;
        margin-bottom: 1.25em;
        padding-left: 1.625em;
    }
    
    .prose ol {
        list-style-type: decimal;
        margin-top: 1.25em;
        margin-bottom: 1.25em;
        padding-left: 1.625em;
    }
    
    .prose li {
        margin-top: 0.5em;
        margin-bottom: 0.5em;
    }
</style>
@endpush
