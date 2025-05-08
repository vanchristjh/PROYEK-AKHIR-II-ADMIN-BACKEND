@extends('layouts.dashboard')

@section('title', $material->title)

@section('header', 'Detail Materi Pembelajaran')

@section('navigation')
    @include('guru.partials.sidebar')
@endsection

@section('content')
    <div class="mb-6">
        <a href="{{ route('guru.materials.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
            <i class="fas fa-chevron-left mr-2 text-sm"></i>
            <span>Kembali ke Daftar Materi</span>
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50 mb-6">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white">
            <div class="flex items-start justify-between">
                <div class="flex items-start">
                    <div class="bg-blue-100 text-blue-600 p-3 rounded-full mr-4">
                        <i class="fas fa-book-open text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                            {{ $material->title }}
                        </h1>
                        <div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-day mr-1"></i>
                                {{ $material->created_at->format('d M Y') }}
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

                @if($material->teacher_id == auth()->id())
                <div class="flex space-x-2">
                    <a href="{{ route('guru.materials.edit', $material) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors flex items-center gap-1">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('guru.materials.destroy', $material) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
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
                {!! nl2br(e($material->description)) !!}
            </div>
            
            <!-- Classroom information -->
            <div class="mt-8 pt-6 border-t border-gray-100">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Kelas</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($material->classrooms as $classroom)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-users mr-1.5 text-blue-600"></i>
                            {{ $classroom->name }}
                        </span>
                    @endforeach
                </div>
            </div>
            
            @if($material->file_path)
            <div class="mt-8 pt-6 border-t border-gray-100">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Lampiran Materi</h3>
                <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas {{ $material->file_icon }} {{ $material->file_color }}"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ basename($material->file_path) }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ strtoupper($material->file_extension) }} File
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ asset('storage/' . $material->file_path) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors inline-flex items-center" target="_blank">
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