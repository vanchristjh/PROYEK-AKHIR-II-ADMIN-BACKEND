@extends('layouts.dashboard')

@section('title', $assignment->title)

@section('header', 'Detail Tugas')

@section('navigation')
    <li>
        <a href="{{ route('siswa.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-tachometer-alt text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.schedule.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-calendar-alt text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Jadwal Pelajaran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.assignments.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-3 text-white">
            <i class="fas fa-tasks text-lg w-6"></i>
            <span class="ml-3">Tugas</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.materials.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-book text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Materi Pelajaran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.grades.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-star text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Nilai</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.announcements.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-bullhorn text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Add flash messages for success/error notifications -->
    @if(session('success'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="mb-6">
        <a href="{{ route('siswa.assignments.index') }}" class="text-indigo-600 hover:text-indigo-800 inline-flex items-center">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke daftar tugas
        </a>
    </div>
    
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <!-- Assignment Header -->
        <div class="p-6 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        @if($assignment->deadline < now())
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200 mr-2">
                                <i class="fas fa-exclamation-circle mr-1"></i> Deadline Terlewat
                            </span>
                        @elseif($assignment->deadline->diffInDays(now()) <= 3)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200 mr-2">
                                <i class="fas fa-clock mr-1"></i> Deadline Segera
                            </span>
                        @endif
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                            <i class="fas fa-book mr-1"></i> {{ $assignment->subject->name }}
                        </span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $assignment->title }}</h1>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                        <div class="inline-flex items-center">
                            <i class="fas fa-calendar-alt mr-1 text-gray-400"></i>
                            <span>Dibuat: {{ $assignment->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="inline-flex items-center">
                            <i class="fas fa-clock mr-1 {{ $assignment->deadline < now() ? 'text-red-500' : 'text-gray-400' }}"></i>
                            <span class="{{ $assignment->deadline < now() ? 'text-red-500 font-medium' : '' }}">Deadline: {{ $assignment->deadline->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="inline-flex items-center">
                            <i class="fas fa-chalkboard-teacher mr-1 text-gray-400"></i>
                            <span>Guru: {{ $assignment->createdBy->name ?? 'Unknown' }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 md:mt-0 md:ml-6 text-center md:text-right">
                    @php
                        $isSubmitted = isset($submission) && $submission;
                        $isGraded = $isSubmitted && !is_null($submission->score);
                        $isExpired = $assignment->deadline < now();
                    @endphp
                    
                    @if($isGraded)
                        <div class="mb-3 p-4 bg-green-50 rounded-lg border border-green-100 inline-block">
                            <div class="text-sm text-gray-600 mb-1">Nilai Anda:</div>
                            <div class="text-3xl font-bold text-green-600">{{ $submission->score }}</div>
                        </div>
                    @endif
                    
                    <div>
                        @if($isSubmitted)
                            <span class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium {{ $isGraded ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                <i class="fas fa-{{ $isGraded ? 'check-circle' : 'paper-plane' }} mr-1"></i>
                                {{ $isGraded ? 'Sudah Dinilai' : 'Sudah Dikumpulkan' }}
                            </span>
                        @elseif($isExpired)
                            <span class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Tidak Mengumpulkan
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-exclamation-circle mr-1"></i> Belum Mengumpulkan
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            @if(!$isExpired && !$isSubmitted)
                <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Anda belum mengumpulkan tugas ini. Deadline pada tanggal <strong>{{ $assignment->deadline->format('d M Y, H:i') }}</strong>
                                @if($assignment->deadline->diffInDays(now()) > 0)
                                    ({{ $assignment->deadline->diffInDays(now()) }} hari lagi).
                                @else
                                    ({{ $assignment->deadline->diffInHours(now()) }} jam lagi).
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Assignment Content -->
        <div class="p-6">
            <div class="prose max-w-none mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Deskripsi Tugas</h3>
                <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                    {!! nl2br(e($assignment->description)) !!}
                </div>
            </div>
            
            @if($assignment->file)
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Lampiran Tugas</h3>
                    <div class="flex items-center bg-blue-50 border border-blue-100 p-4 rounded-md">
                        <div class="p-2 bg-blue-100 text-blue-600 rounded-md mr-4">
                            <i class="fas fa-paperclip text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-700 font-medium mb-1">{{ Str::afterLast($assignment->file, '/') }}</p>
                            <p class="text-sm text-gray-500">File tugas yang harus dikerjakan</p>
                        </div>
                        <a href="{{ Storage::url($assignment->file) }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-download mr-1"></i> Download
                        </a>
                    </div>
                </div>
            @endif
            
            <!-- Submission Section -->
            <div class="border-t border-gray-200 pt-8 mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $isSubmitted ? 'Pengumpulan Tugas Anda' : 'Kumpulkan Tugas Anda' }}</h3>
                
                @if($isSubmitted)
                    <div class="bg-white border border-gray-200 rounded-md p-5">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="p-2 bg-{{ $isGraded ? 'green' : 'blue' }}-100 text-{{ $isGraded ? 'green' : 'blue' }}-600 rounded-md">
                                    <i class="fas fa-{{ $isGraded ? 'clipboard-check' : 'file-alt' }} text-lg"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="text-gray-800 font-medium">File yang Anda kumpulkan</h4>
                                        <p class="text-sm text-gray-500">
                                            Dikumpulkan pada {{ $submission->submitted_at->format('d M Y, H:i') }}
                                            @if($submission->submitted_at != $submission->created_at)
                                                (Diperbarui)
                                            @endif
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isGraded ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $isGraded ? 'Sudah Dinilai' : 'Menunggu Penilaian' }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center bg-gray-50 border border-gray-200 p-3 rounded-md mb-4">
                                    <i class="fas fa-file-alt text-gray-500 mr-3"></i>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-700">{{ Str::afterLast($submission->submission_file, '/') }}</p>
                                    </div>
                                    <a href="{{ Storage::url($submission->submission_file) }}" target="_blank" class="text-blue-600 hover:text-blue-800 mr-2">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <a href="{{ Storage::url($submission->submission_file) }}" download class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                                
                                @if($submission->notes)
                                    <div class="mb-4">
                                        <h5 class="text-sm font-medium text-gray-700 mb-1">Catatan:</h5>
                                        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-md border border-gray-200">
                                            {{ $submission->notes }}
                                        </p>
                                    </div>
                                @endif
                                
                                @if($isGraded)
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-700 mb-1">Penilaian:</h5>
                                        <div class="bg-green-50 p-3 rounded-md border border-green-200">
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="font-medium text-gray-700">Nilai:</span>
                                                <span class="font-bold text-lg text-green-600">{{ $submission->score }}</span>
                                            </div>
                                            @if($submission->feedback)
                                                <div class="mt-2">
                                                    <h6 class="text-sm font-medium text-gray-700 mb-1">Feedback:</h6>
                                                    <p class="text-sm text-gray-600">{{ $submission->feedback }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                
                                @if(!$isExpired && !$isGraded)
                                    <div class="mt-4 flex space-x-3">
                                        <button type="button" id="show-edit-form" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-edit mr-1"></i> Edit Pengumpulan
                                        </button>
                                        
                                        <form action="{{ route('siswa.submissions.destroy', $submission->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumpulan tugas ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition-colors">
                                                <i class="fas fa-trash-alt mr-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Edit Submission Form (Hidden by default) -->
                    @if(!$isExpired && !$isGraded)
                        <div id="edit-submission-form" class="mt-6 hidden">
                            <h4 class="text-lg font-medium text-gray-900 mb-3">Edit Pengumpulan Tugas</h4>
                            
                            <form action="{{ route('siswa.submissions.update', $submission->id) }}" method="POST" enctype="multipart/form-data" class="bg-white border border-gray-200 rounded-md p-5">
                                @csrf
                                @method('PUT')
                                <div class="mb-4">
                                    <label for="file" class="block text-sm font-medium text-gray-700 mb-1">File Tugas</label>
                                    <div class="bg-blue-50 p-3 rounded-lg mb-3 border border-blue-100">
                                        <div class="flex items-center">
                                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                            <p class="text-xs text-blue-700">Perhatian: Ukuran file maksimal <strong>2MB</strong>. Untuk file yang lebih besar, silahkan kompres file atau unggah ke Google Drive/cloud storage dan masukkan link sharing-nya di catatan.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="file" name="file" id="edit-file" class="py-2 px-3 block w-full border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Format file: PDF, Word, Excel, PowerPoint. Maksimal 2MB.</p>
                                    <p class="mt-2 text-xs text-gray-500">File saat ini: <strong>{{ Str::afterLast($submission->submission_file, '/') }}</strong></p>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                                    <textarea name="notes" id="edit-notes" rows="3" class="py-2 px-3 block w-full border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ $submission->notes }}</textarea>
                                    <p class="mt-1 text-xs text-gray-500">Tambahkan catatan jika diperlukan (maksimal 500 karakter)</p>
                                </div>
                                
                                <div class="flex justify-end space-x-3">
                                    <button type="button" id="cancel-edit" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm rounded-md hover:bg-gray-50 transition-colors">
                                        Batal
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-paper-plane mr-1"></i> Perbarui Pengumpulan
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                    
                @else
                    <!-- Submit Form -->
                    @if(!$isExpired)
                        <form action="{{ route('siswa.submissions.store', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="bg-white border border-gray-200 rounded-md p-5">
                            @csrf
                            <div class="mb-4">
                                <label for="file" class="block text-sm font-medium text-gray-700 mb-1">File Tugas <span class="text-red-500">*</span></label>
                                <div class="bg-blue-50 p-3 rounded-lg mb-3 border border-blue-100">
                                    <div class="flex items-center">
                                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                        <p class="text-xs text-blue-700">Perhatian: Ukuran file maksimal <strong>2MB</strong>. Untuk file yang lebih besar, silahkan kompres file atau unggah ke Google Drive/cloud storage dan masukkan link sharing-nya di catatan.</p>
                                    </div>
                                </div>
                                <input type="file" name="file" id="file" class="py-2 px-3 block w-full border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                                @error('file')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Format file: PDF, Word, Excel, PowerPoint. Maksimal 2MB.</p>
                            </div>
                            
                            <div class="mb-4">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                                <textarea name="notes" id="notes" rows="3" class="py-2 px-3 block w-full border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Tambahkan catatan jika diperlukan (maksimal 500 karakter)</p>
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 transition-colors">
                                    <i class="fas fa-paper-plane mr-1"></i> Kumpulkan Tugas
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">
                                        Deadline pengumpulan tugas ini telah berakhir pada <strong>{{ $assignment->deadline->format('d M Y, H:i') }}</strong>. 
                                        Anda tidak dapat mengumpulkan tugas ini lagi.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const showEditFormBtn = document.getElementById('show-edit-form');
        const editSubmissionForm = document.getElementById('edit-submission-form');
        const cancelEditBtn = document.getElementById('cancel-edit');
        const fileInputs = document.querySelectorAll('input[type="file"]');
        const submitForms = document.querySelectorAll('form[enctype="multipart/form-data"]');
        
        // File size validation constants
        const MAX_FILE_SIZE = 2; // 2MB limit
        const ALLOWED_FILE_TYPES = [
            'application/pdf', 
            'application/msword', 
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel', 
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint', 
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/zip', 
            'application/x-zip-compressed', 
            'image/jpeg', 
            'image/png', 
            'image/gif', 
            'text/plain'
        ];
        
        // Edit form toggle functionality
        if(showEditFormBtn && editSubmissionForm) {
            showEditFormBtn.addEventListener('click', function() {
                editSubmissionForm.classList.remove('hidden');
                showEditFormBtn.classList.add('hidden');
            });
        }
        
        if(cancelEditBtn && editSubmissionForm && showEditFormBtn) {
            cancelEditBtn.addEventListener('click', function() {
                editSubmissionForm.classList.add('hidden');
                showEditFormBtn.classList.remove('hidden');
            });
        }
        
        // File validation for all file inputs
        fileInputs.forEach(input => {
            input.addEventListener('change', function() {
                validateFile(this);
            });
        });
        
        // Form submission validation
        submitForms.forEach(form => {
            form.addEventListener('submit', function(event) {
                const fileInput = this.querySelector('input[type="file"]');
                if (fileInput && fileInput.files.length > 0) {
                    if (!validateFile(fileInput)) {
                        event.preventDefault();
                        return false;
                    }
                }
            });
        });
        
        // File validation function
        function validateFile(fileInput) {
            if (fileInput.files && fileInput.files[0]) {
                const file = fileInput.files[0];
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // Size in MB
                
                // Check file size
                if (fileSize > MAX_FILE_SIZE) {
                    alert(`File terlalu besar (${fileSize}MB). Ukuran maksimal yang diizinkan adalah ${MAX_FILE_SIZE}MB. Silahkan kompres file atau gunakan layanan seperti Google Drive untuk berbagi file yang lebih besar.`);
                    fileInput.value = ''; // Clear the file input
                    return false;
                }
                
                // Optional: Check file type
                // const fileType = file.type;
                // if (!ALLOWED_FILE_TYPES.includes(fileType)) {
                //     alert(`Jenis file tidak diperbolehkan. Gunakan format PDF, Word, Excel, PowerPoint, atau ZIP.`);
                //     fileInput.value = ''; // Clear the file input
                //     return false;
                // }
                
                return true;
            }
            return true;
        }
    });
</script>
@endpush

@push('styles')
<style>
    .prose {
        max-width: 65ch;
        color: #374151;
    }
    
    .prose p {
        margin-top: 1.25em;
        margin-bottom: 1.25em;
    }
</style>
@endpush
