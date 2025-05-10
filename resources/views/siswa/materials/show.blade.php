@extends('layouts.dashboard')

@section('title', $material->title)

@section('header', 'Detail Materi Pembelajaran')

@section('content')
    <div class="mb-6">
        <a href="{{ route('siswa.materials.index') }}" class="inline-flex items-center text-purple-600 hover:text-purple-800 transition-colors">
            <i class="fas fa-chevron-left mr-2 text-sm"></i>
            <span>Kembali ke Daftar Materi</span>
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50 mb-6">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-white">
            <div class="flex items-start justify-between">
                <div class="flex items-start">
                    <div class="bg-purple-100 text-purple-600 p-3 rounded-full mr-4">
                        <i class="fas fa-book-open text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                            {{ $material->title }}
                            @if($material->isNew())
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 animate-pulse-slow">
                                    <i class="fas fa-certificate mr-1 text-green-500"></i> Baru
                                </span>
                            @endif
                        </h1>
                        <div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-day mr-1"></i>
                                {{ $material->publish_date->format('d M Y') }}
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-user mr-1"></i>
                                {{ $material->teacher->name ?? 'Unknown' }}
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-book mr-1"></i>
                                {{ $material->subject->name ?? 'Unknown Subject' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="prose max-w-none">
                {!! nl2br(e($material->description)) !!}
            </div>
            
            @if($material->file_path)
            <div class="mt-8 pt-6 border-t border-gray-100">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Lampiran Materi</h3>
                <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex-shrink-0 h-10 w-10 rounded-lg flex items-center justify-center" style="background-color: rgba({{ rand(100, 200) }}, {{ rand(100, 200) }}, {{ rand(100, 200) }}, 0.1);">
                        <i class="fas {{ $material->file_icon }} {{ $material->file_color }}"></i>
                    </div>
                    <div class="min-w-0 flex-1 ml-4">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ basename($material->file_path) }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ strtoupper($material->file_extension) }} File
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ asset('storage/' . $material->file_path) }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors inline-flex items-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5" download>
                            <i class="fas fa-download mr-2"></i>
                            <span>Download File</span>
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 text-sm text-gray-600">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-purple-500 mr-2"></i>
            <p>Materi pembelajaran ini dibuat pada {{ $material->created_at->format('d F Y') }} dan terakhir diperbarui pada {{ $material->updated_at->format('d F Y H:i') }}</p>
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
    
    .animate-pulse-slow {
        animation: pulse-slow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse-slow {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }
</style>
@endpush
