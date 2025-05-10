@extends('layouts.dashboard')

@section('title', 'Detail Pengumpulan Tugas')

@section('header', 'Penilaian Tugas')

@section('navigation')
    <li>
        <a href="{{ route('guru.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-indigo-700 transition-all duration-200">
                <i class="fas fa-tachometer-alt text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.materials.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-green-700/50 transition-all duration-200">
                <i class="fas fa-book text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Materi Pelajaran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.assignments.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-2.5 group relative text-white">
            <div class="p-1.5 rounded-lg bg-green-800 transition-all duration-200">
                <i class="fas fa-tasks text-lg w-5 h-5 flex items-center justify-center text-white"></i>
            </div>
            <span class="ml-3">Tugas</span>
            <span class="absolute inset-y-0 left-0 w-1 bg-green-400 rounded-tr-md rounded-br-md"></span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.grades.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-purple-700/50 transition-all duration-200">
                <i class="fas fa-star text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Penilaian</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.attendance.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-purple-700/50 transition-all duration-200">
                <i class="fas fa-clipboard-check text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Kehadiran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.announcements.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-red-700/50 transition-all duration-200">
                <i class="fas fa-bullhorn text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="mb-6">
        <a href="{{ route('guru.submissions.index', $assignment->id) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition-colors">
            <i class="fas fa-chevron-left mr-2 text-sm"></i>
            <span>Kembali ke Daftar Pengumpulan</span>
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

    <!-- Assignment and Submission Info Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 mb-6">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-green-50 to-white">
            <div class="flex items-start">
                <div class="bg-green-100 text-green-600 p-3 rounded-full mr-4">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $assignment->title }}</h2>
                    <div class="flex items-center gap-4 text-sm text-gray-500">
                        <div class="flex items-center">
                            <i class="fas fa-book mr-1"></i>
                            <span>{{ $assignment->subject->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-users mr-1"></i>
                            <span>{{ $assignment->classroom->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar-day mr-1"></i>
                            <span>Deadline: {{ $assignment->deadline->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="flex items-center mb-5">
                <div class="bg-indigo-100 text-indigo-600 p-3 rounded-full mr-4">
                    <i class="fas fa-user-graduate text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">{{ $submission->student->name ?? 'Unknown Student' }}</h3>
                    <p class="text-gray-500">NIS: {{ $submission->student->nis ?? 'N/A' }}</p>
                </div>
                
                <div class="ml-auto">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $submission->submitted_at->gt($assignment->deadline) ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-green-100 text-green-800 border border-green-200' }}">
                        <i class="fas fa-{{ $submission->submitted_at->gt($assignment->deadline) ? 'clock' : 'check-circle' }} mr-1"></i>
                        {{ $submission->submitted_at->gt($assignment->deadline) ? 'Terlambat' : 'Tepat Waktu' }}
                    </span>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Waktu Pengumpulan</h4>
                    <p class="text-gray-800">{{ $submission->submitted_at->format('d M Y, H:i') }}</p>
                    @if($submission->submitted_at->gt($assignment->deadline))
                        <p class="text-red-500 text-xs mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            Terlambat {{ $submission->submitted_at->diffForHumans($assignment->deadline, ['parts' => 2]) }}
                        </p>
                    @endif
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Status</h4>
                    @if($submission->score !== null)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i> Sudah Dinilai
                        </span>
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
                    <div class="flex space-x-2">
                        <a href="{{ route('guru.submissions.download', ['assignment' => $assignment->id, 'submission' => $submission->id]) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors">
                            <i class="fas fa-download mr-1.5"></i> Download
                        </a>
                        <a href="{{ route('guru.submissions.preview', ['assignment' => $assignment->id, 'submission' => $submission->id]) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 rounded-md hover:bg-indigo-200 transition-colors" target="_blank">
                            <i class="fas fa-eye mr-1.5"></i> Preview
                        </a>
                    </div>
                </div>
            </div>
            
            @if($submission->notes)
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Catatan Siswa</h4>
                    <div class="bg-gray-50 p-4 border border-gray-200 rounded-lg">
                        <p class="text-gray-800">{{ $submission->notes }}</p>
                    </div>
                </div>
            @endif
            
            <!-- Grading Section -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Penilaian</h3>
                
                <form action="{{ route('guru.submissions.grade', ['assignment' => $assignment->id, 'submission' => $submission->id]) }}" method="POST" id="grading-form">
                    @csrf
                    @method('PUT')
                    
                    <div class="bg-white border border-gray-200 rounded-lg p-5">
                        <div class="mb-4">
                            <label for="score" class="block text-sm font-medium text-gray-700 mb-1">Nilai <span class="text-red-500">*</span></label>
                            <div class="flex items-center">
                                <input type="number" name="score" id="score" min="0" max="{{ $assignment->max_score }}" 
                                    value="{{ old('score', $submission->score) }}" 
                                    class="mr-2 w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <span class="text-gray-500 text-sm">/ {{ $assignment->max_score }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Nilai maksimum: {{ $assignment->max_score }}</p>
                            @error('score')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="feedback" class="block text-sm font-medium text-gray-700 mb-1">Feedback untuk Siswa</label>
                            <textarea name="feedback" id="feedback" rows="4" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                >{{ old('feedback', $submission->feedback) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Berikan umpan balik yang membantu siswa memahami nilai yang diberikan</p>
                            @error('feedback')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" id="reset-form" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                                <i class="fas fa-undo mr-1.5"></i> Reset
                            </button>
                            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                <i class="fas fa-save mr-1.5"></i> Simpan Penilaian
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scoreInput = document.getElementById('score');
        const maxScore = {{ $assignment->max_score }};
        const resetButton = document.getElementById('reset-form');
        const form = document.getElementById('grading-form');
        
        // Validate score input
        scoreInput.addEventListener('input', function() {
            let value = parseInt(this.value);
            
            if (isNaN(value)) {
                this.value = '';
            } else {
                // Ensure score is not negative
                if (value < 0) {
                    this.value = 0;
                }
                
                // Ensure score does not exceed max score
                if (value > maxScore) {
                    this.value = maxScore;
                }
            }
        });
        
        // Reset form button
        resetButton.addEventListener('click', function() {
            const originalScore = {{ $submission->score ?? 'null' }};
            const originalFeedback = `{{ $submission->feedback ?? '' }}`;
            
            if (originalScore !== null) {
                scoreInput.value = originalScore;
            } else {
                scoreInput.value = '';
            }
            
            document.getElementById('feedback').value = originalFeedback;
        });
        
        // Form submission validation
        form.addEventListener('submit', function(event) {
            const score = scoreInput.value.trim();
            
            if (!score) {
                event.preventDefault();
                alert('Mohon masukkan nilai untuk tugas ini.');
                scoreInput.focus();
                return false;
            }
            
            const numScore = parseInt(score);
            if (isNaN(numScore)) {
                event.preventDefault();
                alert('Nilai harus berupa angka.');
                scoreInput.focus();
                return false;
            }
            
            if (numScore < 0 || numScore > maxScore) {
                event.preventDefault();
                alert(`Nilai harus berada di antara 0 dan ${maxScore}.`);
                scoreInput.focus();
                return false;
            }
        });
    });
</script>
@endpush

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
