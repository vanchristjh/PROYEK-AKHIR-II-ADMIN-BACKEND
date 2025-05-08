@extends('layouts.dashboard')

@section('title', 'Buat Materi Pelajaran')

@section('header', 'Buat Materi Pelajaran')

@section('navigation')
    <li>
        <a href="{{ route('guru.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-tachometer-alt text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.materials.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-3 text-white">
            <i class="fas fa-book text-lg w-6"></i>
            <span class="ml-3">Materi Pelajaran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.assignments.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-tasks text-lg w-6 text-indigo-300"></i>
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
@endsection

@section('content')
    <!-- Header with animation -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-book-open text-9xl"></i>
        </div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Buat Materi Pelajaran</h2>
            <p class="text-blue-100">Bagikan materi pembelajaran kepada siswa.</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 transform transition hover:shadow-md">
        <div class="p-6">
            <form action="{{ route('guru.materials.store') }}" method="POST" enctype="multipart/form-data" class="animate-fade-in" id="materialForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group mb-5 md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Materi</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-heading text-gray-400"></i>
                            </div>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-shadow duration-300" required>
                        </div>
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5 md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Materi</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <textarea name="description" id="description" rows="6" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-shadow duration-300" required>{{ old('description') }}</textarea>
                        </div>
                        @error('description')
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
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-shadow duration-300" required>
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('subject_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5">
                        <label for="classroom_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas (Pilih satu atau lebih)</label>
                        <div class="mt-1 bg-white rounded-lg border border-gray-300 px-3 py-2 focus-within:ring focus-within:ring-blue-200 focus-within:ring-opacity-50 focus-within:border-blue-500">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach($classrooms as $classroom)
                                    <div class="flex items-center">
                                        <input type="checkbox" id="class_{{ $classroom->id }}" name="classroom_id[]" value="{{ $classroom->id }}" 
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
                                            {{ (old('classroom_id') && in_array($classroom->id, old('classroom_id'))) ? 'checked' : '' }}>
                                        <label for="class_{{ $classroom->id }}" class="ml-2 text-sm text-gray-700">{{ $classroom->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @error('classroom_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5 md:col-span-2">
                        <label for="attachment" class="block text-sm font-medium text-gray-700 mb-1">Lampiran Materi</label>
                        <div class="mt-1 relative">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-file-upload text-gray-400 text-2xl" id="attachment-icon"></i>
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="attachment" id="attachment" 
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-shadow duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="text-xs text-gray-500 mt-1">Maksimal 20MB. Format: pdf, doc, docx, xls, xlsx, ppt, pptx, jpg, png, mp4, zip</p>
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
                        <a href="{{ route('guru.materials.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-300">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                        <button type="submit" class="ml-3 px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i> Simpan Materi
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
        color: #3B82F6;
    }
    
    .form-group:focus-within i {
        color: #3B82F6;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Change icon color when file is selected
        const attachmentInput = document.getElementById('attachment');
        const attachmentIcon = document.getElementById('attachment-icon');
        
        if (attachmentInput && attachmentIcon) {
            attachmentInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    attachmentIcon.classList.remove('text-gray-400');
                    attachmentIcon.classList.add('text-blue-500');
                } else {
                    attachmentIcon.classList.remove('text-blue-500');
                    attachmentIcon.classList.add('text-gray-400');
                }
            });
        }

        // Select all checkboxes functionality
        const selectAllBtn = document.getElementById('select-all-classrooms');
        const classroomCheckboxes = document.querySelectorAll('input[name="classroom_id[]"]');
        
        if (selectAllBtn && classroomCheckboxes.length > 0) {
            selectAllBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const isAllSelected = Array.from(classroomCheckboxes).every(cb => cb.checked);
                
                classroomCheckboxes.forEach(checkbox => {
                    checkbox.checked = !isAllSelected;
                });
                
                this.textContent = isAllSelected ? 'Pilih Semua' : 'Batal Pilih';
            });
        }

        const subjectSelect = document.getElementById('subject_id');
        const classroomSelect = document.getElementById('classroom_id');
        const materialTypeSelect = document.getElementById('material_type');
        const fileInput = document.getElementById('file_path');
        const contentInput = document.getElementById('content');
        const contentContainer = document.getElementById('content-container');
        const fileContainer = document.getElementById('file-container');
        const filePreview = document.getElementById('file-preview');
        const linkInput = document.getElementById('link');
        const linkContainer = document.getElementById('link-container');
        const form = document.getElementById('materialForm');
        const errorContainer = document.getElementById('error-container');
        
        // When subject changes, update classrooms
        subjectSelect.addEventListener('change', function() {
            const subjectId = this.value;
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
                    .then(response => response.json())
                    .then(data => {
                        // Remove loading option
                        classroomSelect.removeChild(loadingOption);
                        
                        if (data.length === 0) {
                            const option = document.createElement('option');
                            option.value = '';
                            option.textContent = 'Tidak ada kelas untuk mata pelajaran ini';
                            option.disabled = true;
                            classroomSelect.appendChild(option);
                        } else {
                            data.forEach(classroom => {
                                const option = document.createElement('option');
                                option.value = classroom.id;
                                option.textContent = classroom.name;
                                classroomSelect.appendChild(option);
                            });
                            
                            // If there was a previously selected classroom, try to reselect it
                            const oldClassroomId = "{{ old('classroom_id') }}";
                            if (oldClassroomId) {
                                classroomSelect.value = oldClassroomId;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        classroomSelect.innerHTML = '<option value="">Error memuat kelas</option>';
                    });
            } else {
                classroomSelect.innerHTML = '<option value="">Pilih Kelas</option>';
            }
        });
        
        // When material type changes, show/hide appropriate inputs
        materialTypeSelect.addEventListener('change', function() {
            const value = this.value;
            
            if (value === 'text') {
                contentContainer.classList.remove('hidden');
                fileContainer.classList.add('hidden');
                linkContainer.classList.add('hidden');
            } else if (value === 'file') {
                contentContainer.classList.add('hidden');
                fileContainer.classList.remove('hidden');
                linkContainer.classList.add('hidden');
            } else if (value === 'link') {
                contentContainer.classList.add('hidden');
                fileContainer.classList.add('hidden');
                linkContainer.classList.remove('hidden');
            }
        });
        
        // File input preview
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // Size in MB
                const fileName = file.name;
                const fileType = file.type;
                
                // Clear previous preview
                filePreview.innerHTML = '';
                
                // Determine appropriate icon based on file type
                let iconClass = 'fa-file';
                let colorClass = 'text-gray-500';
                
                if (fileType.includes('pdf')) {
                    iconClass = 'fa-file-pdf';
                    colorClass = 'text-red-500';
                } else if (fileType.includes('word') || fileName.endsWith('.doc') || fileName.endsWith('.docx')) {
                    iconClass = 'fa-file-word';
                    colorClass = 'text-blue-500';
                } else if (fileType.includes('spreadsheet') || fileName.endsWith('.xls') || fileName.endsWith('.xlsx')) {
                    iconClass = 'fa-file-excel';
                    colorClass = 'text-green-500';
                } else if (fileType.includes('presentation') || fileName.endsWith('.ppt') || fileName.endsWith('.pptx')) {
                    iconClass = 'fa-file-powerpoint';
                    colorClass = 'text-orange-500';
                } else if (fileType.includes('image')) {
                    iconClass = 'fa-file-image';
                    colorClass = 'text-purple-500';
                } else if (fileType.includes('zip') || fileType.includes('archive') || fileName.endsWith('.zip')) {
                    iconClass = 'fa-file-archive';
                    colorClass = 'text-yellow-500';
                } else if (fileType.includes('video')) {
                    iconClass = 'fa-file-video';
                    colorClass = 'text-pink-500';
                }
                
                // Create file preview element
                const previewEl = document.createElement('div');
                previewEl.className = 'flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 mt-2';
                previewEl.innerHTML = `
                    <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center">
                        <i class="fas ${iconClass} ${colorClass} text-xl"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <div class="text-sm font-medium text-gray-900 truncate" title="${fileName}">${fileName}</div>
                        <div class="text-xs text-gray-500">${fileSize} MB</div>
                    </div>
                    <button type="button" id="remove-file" class="text-gray-400 hover:text-red-500 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                
                filePreview.appendChild(previewEl);
                
                // Add remove button functionality
                document.getElementById('remove-file').addEventListener('click', function() {
                    fileInput.value = '';
                    filePreview.innerHTML = '';
                });
                
                // Validate file size
                if (file.size > 20 * 1024 * 1024) { // 20MB
                    showError('File terlalu besar. Ukuran maksimal adalah 20MB.');
                } else {
                    clearError();
                }
            }
        });
        
        // Link validation
        linkInput.addEventListener('input', function() {
            const value = this.value.trim();
            if (value && !isValidUrl(value)) {
                showError('URL tidak valid. Pastikan dimulai dengan http:// atau https://');
            } else {
                clearError();
            }
        });
        
        // Form validation
        form.addEventListener('submit', function(event) {
            clearError();
            let hasError = false;
            
            // Title validation
            const title = document.getElementById('title').value.trim();
            if (!title) {
                showError('Judul materi tidak boleh kosong');
                hasError = true;
            }
            
            // Check material type specific validations
            const materialType = materialTypeSelect.value;
            
            if (materialType === 'text') {
                const content = contentInput.value.trim();
                if (!content) {
                    showError('Konten materi tidak boleh kosong');
                    hasError = true;
                }
            } else if (materialType === 'file') {
                if (!fileInput.files || fileInput.files.length === 0) {
                    showError('Pilih file untuk diunggah');
                    hasError = true;
                } else if (fileInput.files[0].size > 20 * 1024 * 1024) { // 20MB
                    showError('File terlalu besar. Ukuran maksimal adalah 20MB.');
                    hasError = true;
                }
            } else if (materialType === 'link') {
                const link = linkInput.value.trim();
                if (!link) {
                    showError('URL tidak boleh kosong');
                    hasError = true;
                } else if (!isValidUrl(link)) {
                    showError('URL tidak valid. Pastikan dimulai dengan http:// atau https://');
                    hasError = true;
                }
            }
            
            if (hasError) {
                event.preventDefault();
            }
        });
        
        // Initialize based on existing values
        if (subjectSelect.value) {
            subjectSelect.dispatchEvent(new Event('change'));
        }
        
        materialTypeSelect.dispatchEvent(new Event('change'));
        
        // Helper functions
        function showError(message) {
            errorContainer.innerHTML = `
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">${message}</p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        function clearError() {
            errorContainer.innerHTML = '';
        }
        
        function isValidUrl(url) {
            try {
                new URL(url);
                return true;
            } catch (e) {
                return false;
            }
        }
    });
</script>
@endpush
