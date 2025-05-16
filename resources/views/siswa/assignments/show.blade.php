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
        <a href="{{ route('siswa.material.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
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
                @if($isSubmitted)
                    <div class="bg-white border border-gray-200 rounded-md p-5">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center text-green-500">
                                <i class="fas fa-check-circle text-2xl"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-lg font-medium text-gray-900">Tugas Sudah Dikumpulkan</h4>
                                <p class="text-sm text-gray-500 mt-1">
                                    Dikumpulkan pada {{ $submission->created_at->format('d M Y, H:i') }}
                                </p>
                                <div class="flex items-center mt-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-lg flex items-center justify-center {{ $submission->file_color ?? 'bg-gray-200' }}">
                                        <i class="fas {{ $submission->file_icon ?? 'fa-file' }} text-white"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ basename($submission->file_path) }}</p>
                                        <p class="text-xs text-gray-500">{{ $submission->file_size ?? 'Unknown size' }}</p>
                                    </div>
                                    <a href="{{ route('siswa.submissions.download', $submission->id) }}" class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-lg text-sm transition-colors">
                                        <i class="fas fa-download mr-1"></i> Unduh
                                    </a>
                                </div>
                                
                                @if($isGraded)
                                    <div class="mt-3 p-3 bg-yellow-50 border border-yellow-100 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-yellow-100 flex items-center justify-center text-yellow-600">
                                                <i class="fas fa-star text-lg"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">Nilai: {{ $submission->score }}</p>
                                                <p class="text-xs text-gray-500">Dinilai pada {{ $submission->graded_at->format('d M Y, H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if(!$isExpired && !$isGraded)
                                    <div class="mt-4 flex space-x-3">
                                        <button id="editSubmissionBtn" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                        <button id="deleteSubmissionBtn" class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition-colors">
                                            <i class="fas fa-trash-alt mr-1"></i> Hapus
                                        </button>
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
                                    <input type="file" name="file" id="edit-file" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                    <p class="text-xs text-gray-500 mt-1">Format: PDF, DOCX, XLSX, ZIP, JPG, PNG (Max: 100MB)</p>
                                </div>
                                
                                <div class="flex justify-end space-x-2 mt-4">
                                    <button type="button" id="cancelEdit" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                                        Batal
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                        Perbarui
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Delete Confirmation Modal -->
                        <div id="delete-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-modal="true" role="dialog">
                            <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" id="modal-backdrop"></div>
                                <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
                                <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="sm:flex sm:items-start">
                                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                                            </div>
                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                <h3 class="text-lg font-medium leading-6 text-gray-900">Hapus Pengumpulan Tugas</h3>
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-500">
                                                        Apakah Anda yakin ingin menghapus pengumpulan tugas ini? Tindakan ini tidak dapat dibatalkan.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                        <form action="{{ route('siswa.submissions.destroy', $submission->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                                                Hapus
                                            </button>
                                        </form>
                                        <button type="button" id="cancel-delete" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                            Batal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- If no submission yet and not expired, show submission form -->
                    @if(!$isExpired)
                        <div class="bg-white border border-gray-200 rounded-md p-5 mb-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center text-blue-500">
                                    <i class="fas fa-upload text-2xl"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h4 class="text-lg font-medium text-gray-900">Kumpulkan Tugas</h4>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Silakan unggah file tugas Anda sebelum deadline.
                                    </p>
                                    
                                    <form action="{{ route('siswa.submissions.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                                        @csrf
                                        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                                        
                                        <div class="mb-4">
                                            <label for="file" class="block text-sm font-medium text-gray-700 mb-1">File Tugas</label>
                                            <input type="file" name="file" id="file" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                            <p class="text-xs text-gray-500 mt-1">Format: PDF, DOCX, XLSX, ZIP, JPG, PNG (Max: 100MB)</p>
                                        </div>
                                        
                                        <div class="flex justify-end">
                                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white font-medium rounded-lg shadow-sm hover:from-green-700 hover:to-green-800 focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition-all">
                                                <i class="fas fa-paper-plane mr-2"></i> Kirim Tugas
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Edit submission functionality
        const editBtn = document.getElementById('editSubmissionBtn');
        const cancelEditBtn = document.getElementById('cancelEdit');
        const editForm = document.getElementById('edit-submission-form');
        
        if(editBtn) {
            editBtn.addEventListener('click', function() {
                editForm.classList.remove('hidden');
            });
        }
        
        if(cancelEditBtn) {
            cancelEditBtn.addEventListener('click', function() {
                editForm.classList.add('hidden');
            });
        }
        
        // Delete confirmation modal
        const deleteBtn = document.getElementById('deleteSubmissionBtn');
        const deleteModal = document.getElementById('delete-modal');
        const cancelDeleteBtn = document.getElementById('cancel-delete');
        const modalBackdrop = document.getElementById('modal-backdrop');
        
        if(deleteBtn) {
            deleteBtn.addEventListener('click', function() {
                deleteModal.classList.remove('hidden');
            });
        }
        
        if(cancelDeleteBtn) {
            cancelDeleteBtn.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
            });
        }
        
        if(modalBackdrop) {
            modalBackdrop.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
            });
        }

        // File validation for both forms
        const fileInput = document.getElementById('file');
        const editFileInput = document.getElementById('edit-file');
        
        function validateFile(input) {
            if(input && input.files && input.files[0]) {
                const file = input.files[0];
                const fileSize = Math.round((file.size / 1024 / 1024) * 100) / 100; // MB
                if(fileSize > 100) {
                    alert(`File terlalu besar (${fileSize}MB). Ukuran maksimal yang diizinkan adalah 100MB.`);
                    input.value = '';
                    return false;
                }
                
                const allowedTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'zip'];
                const fileExt = file.name.split('.').pop().toLowerCase();
                
                if(!allowedTypes.includes(fileExt)) {
                    alert(`Jenis file "${fileExt}" tidak diizinkan. Gunakan format yang diizinkan.`);
                    input.value = '';
                    return false;
                }
                return true;
            }
            return false;
        }
        
        if(fileInput) {
            fileInput.addEventListener('change', function() {
                validateFile(this);
            });
        }
        
        if(editFileInput) {
            editFileInput.addEventListener('change', function() {
                validateFile(this);
            });
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
@endsection
