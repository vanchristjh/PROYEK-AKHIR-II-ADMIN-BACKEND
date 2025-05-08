@extends('layouts.dashboard')

@section('title', 'Edit Tugas')

@section('header', 'Edit Tugas')

@section('navigation')
    <li>
        <a href="{{ route('guru.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-tachometer-alt text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.materials.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-book text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Materi Pelajaran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.assignments.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-3 text-white">
            <i class="fas fa-tasks text-lg w-6"></i>
            <span class="ml-3">Tugas</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.grades.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-star text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Penilaian</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.attendance.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-clipboard-check text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Kehadiran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.announcements.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-bullhorn text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
@endsection

@section('content')
    <!-- Header with animation -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-edit text-9xl"></i>
        </div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Edit Tugas</h2>
            <p class="text-blue-100">Perbarui detail tugas yang telah dibuat</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 transform transition hover:shadow-md">
        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md shadow-sm animate-fade-in">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md shadow-sm animate-fade-in">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <form action="{{ route('guru.assignments.update', $assignment) }}" method="POST" enctype="multipart/form-data" class="animate-fade-in">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group mb-5 md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Tugas</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-heading text-gray-400"></i>
                            </div>
                            <input type="text" name="title" id="title" value="{{ old('title', $assignment->title) }}" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-shadow duration-300" required>
                        </div>
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5">
                        <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-book text-gray-400"></i>
                            </div>
                            <select name="subject_id" id="subject_id" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-shadow duration-300" required>
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id', $assignment->subject_id) == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }} ({{ $subject->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('subject_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5">
                        <label for="classroom_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-school text-gray-400"></i>
                            </div>
                            <select name="classroom_id" id="classroom_id" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-shadow duration-300" required>
                                <option value="">Pilih Kelas</option>
                                @foreach($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}" {{ old('classroom_id', $assignment->classroom_id) == $classroom->id ? 'selected' : '' }}>
                                        {{ $classroom->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('classroom_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5 md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Tugas</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <textarea name="description" id="description" rows="5" 
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-shadow duration-300" required>{{ old('description', $assignment->description) }}</textarea>
                        </div>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5">
                        <label for="deadline" class="block text-sm font-medium text-gray-700 mb-1">Deadline (Tenggat Waktu)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                            <input type="datetime-local" name="deadline" id="deadline" 
                                value="{{ old('deadline', $assignment->deadline ? $assignment->deadline->format('Y-m-d\TH:i') : '') }}" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-shadow duration-300" required>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Batas waktu pengumpulan tugas oleh siswa</p>
                        @error('deadline')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5">
                        <label for="max_score" class="block text-sm font-medium text-gray-700 mb-1">Nilai Maksimum</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-star text-gray-400"></i>
                            </div>
                            <input type="number" name="max_score" id="max_score" value="{{ old('max_score', $assignment->max_score) }}" min="1" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-shadow duration-300" required>
                        </div>
                        @error('max_score')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5 md:col-span-2">
                        <label for="attachment" class="block text-sm font-medium text-gray-700 mb-1">Lampiran Tugas (Opsional)</label>
                        <div class="mt-1 relative">
                            @if($assignment->attachment_path)
                                <div class="bg-blue-50 p-3 rounded-lg mb-3 flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                            @php
                                                $fileExtension = pathinfo($assignment->attachment_path, PATHINFO_EXTENSION);
                                                $iconClass = 'fas fa-file text-blue-500';
                                                
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
                                            <i class="{{ $iconClass }} text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Lampiran saat ini</p>
                                            <p class="text-xs text-gray-500">{{ basename($assignment->attachment_path) }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="{{ asset('storage/'.$assignment->attachment_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium mr-2">
                                            <i class="fas fa-download mr-1"></i> Unduh
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="flex items-center mb-3">
                                    <input type="checkbox" name="remove_attachment" id="remove_attachment" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    <label for="remove_attachment" class="ml-2 block text-sm text-red-700">
                                        Hapus lampiran saat ini
                                    </label>
                                </div>
                            @endif
                            
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-file-upload text-gray-400 text-2xl" id="attachment-icon"></i>
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="attachment" id="attachment"
                                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip"
                                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-shadow duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    <p class="text-xs text-gray-500 mt-1">Maksimal 10MB. Format: pdf, doc, docx, xls, xlsx, ppt, pptx, jpg, png, zip. Kosongkan jika tidak ingin mengubah.</p>
                                </div>
                            </div>
                        </div>
                        @error('attachment')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="border-t border-gray-200 mt-8 pt-5">
                    <div class="flex justify-end">
                        <a href="{{ route('guru.assignments.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-300">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                        <button type="submit" class="ml-3 px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i> Perbarui Tugas
                        </button>
                    </div>
                </div>
            </form>
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
    
    .form-group:focus-within label {
        color: #6366f1;
    }
    
    .form-group:focus-within i {
        color: #6366f1;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const subjectSelect = document.getElementById('subject_id');
        const classroomSelect = document.getElementById('classroom_id');
        const attachmentInput = document.getElementById('attachment');
        const removeAttachmentCheckbox = document.getElementById('remove_attachment');
        const form = document.querySelector('form');
        
        // File validation before submission
        form.addEventListener('submit', function(event) {
            // Validate file size and type if a new file is selected
            if (attachmentInput.files && attachmentInput.files[0]) {
                const file = attachmentInput.files[0];
                const fileSize = file.size / 1024 / 1024; // in MB
                const allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'zip'];
                const fileExtension = file.name.split('.').pop().toLowerCase();
                
                // Check file size
                if (fileSize > 10) {
                    event.preventDefault();
                    alert('Ukuran file terlalu besar. Maksimal 10MB.');
                    return false;
                }
                
                // Check file type
                if (!allowedExtensions.includes(fileExtension)) {
                    event.preventDefault();
                    alert('Format file tidak didukung. Format yang diizinkan: pdf, doc, docx, xls, xlsx, ppt, pptx, jpg, png, zip');
                    return false;
                }
            }
            
            // Don't allow both removing and adding a file at the same time
            if (removeAttachmentCheckbox && removeAttachmentCheckbox.checked && attachmentInput.files && attachmentInput.files.length > 0) {
                event.preventDefault();
                alert('Anda tidak dapat menghapus dan mengunggah file baru secara bersamaan. Silakan pilih salah satu.');
                return false;
            }
        });
        
        // Function to load classrooms for a subject
        function loadClassrooms(subjectId, selectedClassroomId = null) {
            if (subjectId) {
                // Clear classroom select
                classroomSelect.innerHTML = '<option value="">Pilih Kelas</option>';
                
                // Show loading indicator
                const loadingOption = document.createElement('option');
                loadingOption.textContent = 'Memuat kelas...';
                loadingOption.disabled = true;
                classroomSelect.appendChild(loadingOption);
                
                // Get classrooms for this subject
                fetch(`/guru/subjects/${subjectId}/classrooms`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Remove loading indicator
                        classroomSelect.removeChild(loadingOption);
                        
                        if (data.length === 0) {
                            const noOption = document.createElement('option');
                            noOption.textContent = 'Tidak ada kelas untuk mata pelajaran ini';
                            noOption.disabled = true;
                            classroomSelect.appendChild(noOption);
                        } else {
                            // Add each classroom option
                            data.forEach(classroom => {
                                const option = document.createElement('option');
                                option.value = classroom.id;
                                option.textContent = classroom.name;
                                
                                // Select the current classroom if it matches
                                if (selectedClassroomId && classroom.id == selectedClassroomId) {
                                    option.selected = true;
                                }
                                
                                classroomSelect.appendChild(option);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error loading classrooms:', error);
                        classroomSelect.innerHTML = '<option value="">Error memuat kelas</option>';
                        
                        // Add back the current classroom if it exists
                        if (selectedClassroomId) {
                            const currentOption = document.createElement('option');
                            currentOption.value = selectedClassroomId;
                            currentOption.textContent = "{{ $assignment->classroom->name ?? '' }}";
                            currentOption.selected = true;
                            classroomSelect.appendChild(currentOption);
                        }
                    });
            } else {
                classroomSelect.innerHTML = '<option value="">Pilih Kelas</option>';
            }
        }
        
        // When subject changes, update classrooms
        subjectSelect.addEventListener('change', function() {
            const subjectId = this.value;
            loadClassrooms(subjectId);
        });
        
        // Initial load of classrooms if subject is already selected
        if (subjectSelect.value) {
            loadClassrooms(subjectSelect.value, "{{ $assignment->classroom_id }}");
        }
        
        // Update attachment icon when file is selected
        if (attachmentInput) {
            attachmentInput.addEventListener('change', function() {
                const icon = document.getElementById('attachment-icon');
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const fileType = file.type;
                    const fileSize = file.size / 1024 / 1024; // in MB
                    
                    // Update icon based on file type
                    if (fileType.includes('pdf')) {
                        icon.className = 'fas fa-file-pdf text-red-500 text-2xl';
                    } else if (fileType.includes('word') || fileType.includes('doc')) {
                        icon.className = 'fas fa-file-word text-blue-500 text-2xl';
                    } else if (fileType.includes('spreadsheet') || fileType.includes('excel')) {
                        icon.className = 'fas fa-file-excel text-green-500 text-2xl';
                    } else if (fileType.includes('presentation') || fileType.includes('powerpoint')) {
                        icon.className = 'fas fa-file-powerpoint text-orange-500 text-2xl';
                    } else if (fileType.includes('image')) {
                        icon.className = 'fas fa-file-image text-purple-500 text-2xl';
                    } else if (fileType.includes('zip') || fileType.includes('archive')) {
                        icon.className = 'fas fa-file-archive text-yellow-500 text-2xl';
                    } else {
                        icon.className = 'fas fa-file text-gray-500 text-2xl';
                    }
                    
                    // Show file size warning if too large
                    if (fileSize > 10) {
                        alert('Peringatan: Ukuran file melebihi 10MB. File tidak akan dapat diunggah.');
                    }
                    
                    // If a file is selected, uncheck the remove attachment checkbox
                    if (removeAttachmentCheckbox) {
                        removeAttachmentCheckbox.checked = false;
                    }
                }
            });
        }
        
        // If "remove attachment" is checked, clear the file input
        if (removeAttachmentCheckbox) {
            removeAttachmentCheckbox.addEventListener('change', function() {
                if (this.checked && attachmentInput) {
                    attachmentInput.value = '';
                    const icon = document.getElementById('attachment-icon');
                    if (icon) {
                        icon.className = 'fas fa-file-upload text-gray-400 text-2xl';
                    }
                }
            });
        }
    });
</script>
@endpush