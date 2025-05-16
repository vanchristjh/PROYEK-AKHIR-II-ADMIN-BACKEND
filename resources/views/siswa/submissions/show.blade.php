@extends('layouts.dashboard')

@section('title', 'Detail Pengumpulan Tugas - URGENT UPDATE')

@section('header', 'Detail Pengumpulan Tugas - URGENT UPDATE')

<!-- DEBUGGING TEST - PLEASE CONFIRM IF THIS APPEARS IN THE UI -->

@section('navigation')
@endsection

@section('content')
<div class="mb-6">
    <a href="{{ route('siswa.assignments.show', $submission->assignment_id) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition-colors">
        <i class="fas fa-chevron-left mr-2 text-sm"></i>
        <span>Kembali ke Detail Tugas</span>
    </a>
</div>

@if(session('success'))
    <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-md">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-md">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif

<!-- Assignment and Submission Info Card - UPDATED -->
    <div class="bg-blue-100 rounded-xl shadow-sm overflow-hidden border border-blue-300 mb-6">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-green-50 to-white">
            <div class="flex items-start">
                <div class="bg-green-100 text-green-600 p-3 rounded-full mr-4">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $submission->assignment->title }}</h2>
                    <div class="flex items-center gap-4 text-sm text-gray-500">
                        <div class="flex items-center">
                            <i class="fas fa-book mr-1"></i>
                            <span>{{ $submission->assignment->subject->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-user-tie mr-1"></i>
                            <span>{{ $submission->assignment->teacher->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar-day mr-1"></i>
                            <span>Deadline: {{ $submission->assignment->deadline->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Waktu Pengumpulan</h4>
                    <p class="text-gray-800">{{ $submission->submitted_at->format('d M Y, H:i') }}</p>
                    @if($submission->isLate())
                        <p class="text-red-500 text-xs mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            Terlambat {{ $submission->submitted_at->diffForHumans($submission->assignment->deadline, ['parts' => 2]) }}
                        </p>
                    @else
                        <p class="text-green-500 text-xs mt-1">
                            <i class="fas fa-check-circle mr-1"></i>
                            Tepat Waktu
                        </p>
                    @endif
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Status</h4>
                    @if($submission->score !== null)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i> Sudah Dinilai
                        </span>
                        <p class="mt-2 text-gray-800">
                            <span class="font-semibold text-green-600">{{ $submission->score }}</span> / {{ $submission->assignment->max_score }}
                        </p>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i> Belum Dinilai
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-2">File Pengumpulan</h4>
                <div class="flex items-center justify-between bg-gray-50 p-4 border border-gray-200 rounded-lg">
                    @php
                        $fileName = basename($submission->file_path);
                        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                        $iconClass = 'fas fa-file text-gray-500';
                        
                        if (in_array($fileExtension, ['pdf'])) {
                            $iconClass = 'fas fa-file-pdf text-red-500';
                        } elseif (in_array($fileExtension, ['doc', 'docx'])) {
                            $iconClass = 'fas fa-file-word text-blue-500';
                        } elseif (in_array($fileExtension, ['xls', 'xlsx'])) {
                            $iconClass = 'fas fa-file-excel text-green-500';
                        } elseif (in_array($fileExtension, ['ppt', 'pptx'])) {
                            $iconClass = 'fas fa-file-powerpoint text-orange-500';
                        } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                            $iconClass = 'fas fa-file-image text-purple-500';
                        } elseif (in_array($fileExtension, ['zip', 'rar'])) {
                            $iconClass = 'fas fa-file-archive text-yellow-500';
                        }
                    @endphp
                    <div class="flex items-center">
                        <div class="p-2 bg-gray-200 rounded mr-3">
                            <i class="{{ $iconClass }}"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ $fileName }}</p>
                            <p class="text-xs text-gray-500">Submitted file</p>
                        </div>
                    </div>
                    <a href="{{ route('siswa.submissions.download', $submission->id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors">
                        <i class="fas fa-download mr-1.5"></i> Download
                    </a>
                </div>
            </div>

            @if($submission->feedback)
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Feedback dari Guru</h4>
                    <div class="bg-gray-50 p-4 border border-gray-200 rounded-lg">
                        <p class="text-gray-800">{{ $submission->feedback }}</p>
                    </div>
                </div>
            @endif
            
            <!-- Additional options -->
            <div class="border-t border-gray-200 pt-6 flex justify-between">
                <a href="{{ route('siswa.assignments.show', $submission->assignment_id) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-1.5"></i> Kembali
                </a>
                
                @if(!$submission->assignment->deadline->isPast() || $submission->assignment->allow_late_submission)
                    <a href="{{ route('siswa.submissions.edit', $submission->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition-colors">
                        <i class="fas fa-edit mr-1.5"></i> Edit Pengumpulan
                    </a>
                @endif
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
    
    /* Improved input styles */
    input[type="number"].styled-input {
        appearance: textfield;
    }
    
    input[type="number"].styled-input::-webkit-outer-spin-button,
    input[type="number"].styled-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
@endpush
