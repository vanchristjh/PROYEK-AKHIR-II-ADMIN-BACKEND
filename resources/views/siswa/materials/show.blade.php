@extends('layouts.dashboard')

@section('title', $material->title)

@section('header', 'Detail Materi Pembelajaran')

@section('content')
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50 mb-6">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-white">
            <div class="flex items-start justify-between">
                <div class="flex items-start">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $material->title }}</h1>
                        <div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-purple-500 mr-2"></i>
                                <span>{{ $material->created_at->format('d M Y') }}</span>
                            </div>
                            
                            <div class="flex items-center">
                                <i class="fas fa-user text-purple-500 mr-2"></i>
                                <span>{{ $material->teacher->name }}</span>
                            </div>
                            
                            <div class="flex items-center">
                                <i class="fas fa-book text-purple-500 mr-2"></i>
                                <span>{{ $material->subject->name }}</span>
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
                        <i class="fas {{ $material->file_icon ?? 'fa-file' }} {{ $material->file_color ?? 'text-gray-500' }}"></i>
                    </div>
                    <div class="min-w-0 flex-1 ml-4">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ basename($material->file_path) }}</p>
                        @if(isset($material->file_size))
                            <p class="text-xs text-gray-500">{{ $material->file_size }}</p>
                        @endif
                    </div>
                    <div class="ml-4 flex-shrink-0">
                        <a href="{{ route('siswa.materials.download', $material->id) }}" class="font-medium text-purple-600 hover:text-purple-500 flex items-center bg-purple-50 hover:bg-purple-100 px-3 py-1.5 rounded-lg transition-colors">
                            <i class="fas fa-download mr-2"></i>
                            <span>Unduh</span>
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <div class="mt-6">
        <a href="{{ route('siswa.materials.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Materi
        </a>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add any needed scripts here
    });
</script>
@endpush
