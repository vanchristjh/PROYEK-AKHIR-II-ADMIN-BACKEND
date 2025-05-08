@extends('layouts.dashboard')

@section('title', 'Buat Pengumuman Baru')

@section('header', 'Buat Pengumuman')

@section('navigation')
    @include('guru.partials.sidebar')
@endsection

@section('content')
    <!-- Header with animation -->
    <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-bullhorn text-9xl"></i>
        </div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute right-1/3 -top-12 w-36 h-36 bg-red-300/20 rounded-full blur-3xl"></div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Buat Pengumuman Baru</h2>
            <p class="text-red-100">Sampaikan informasi penting kepada siswa dan guru</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 transform transition hover:shadow-md">
        <div class="p-6">
            <form action="{{ route('guru.announcements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                @if ($errors->any())
                    <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Terdapat {{ $errors->count() }} kesalahan pada form:</h3>
                                <ul class="mt-2 text-sm list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Pengumuman <span class="text-red-500">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-heading text-gray-400"></i>
                            </div>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" class="pl-10 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 @error('title') border-red-300 @enderror" placeholder="Masukkan judul pengumuman" required>
                        </div>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Target Audience -->
                    <div>
                        <label for="audience" class="block text-sm font-medium text-gray-700 mb-1">Ditujukan Kepada <span class="text-red-500">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-users text-gray-400"></i>
                            </div>
                            <select name="audience" id="audience" class="pl-10 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 @error('audience') border-red-300 @enderror" required>
                                <option value="all" {{ old('audience') == 'all' ? 'selected' : '' }}>Semua Pengguna</option>
                                <option value="teachers" {{ old('audience') == 'teachers' ? 'selected' : '' }}>Guru</option>
                                <option value="students" {{ old('audience') == 'students' ? 'selected' : '' }}>Siswa</option>
                            </select>
                        </div>
                        @error('audience')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Publish Date -->
                    <div>
                        <label for="publish_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publikasi <span class="text-red-500">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                            <input type="date" name="publish_date" id="publish_date" value="{{ old('publish_date', now()->format('Y-m-d')) }}" class="pl-10 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 @error('publish_date') border-red-300 @enderror" required>
                        </div>
                        @error('publish_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Importance Toggle -->
                    <div class="col-span-2">
                        <div class="flex items-center">
                            <div class="form-switch inline-block align-middle">
                                <input type="checkbox" name="is_important" id="is_important" class="form-switch-checkbox" value="1" {{ old('is_important') ? 'checked' : '' }}>
                                <label class="form-switch-label" for="is_important"></label>
                            </div>
                            <label for="is_important" class="ml-2 block text-sm font-medium text-gray-700 cursor-pointer">
                                Tandai sebagai pengumuman penting
                            </label>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Pengumuman penting akan ditampilkan dengan highlight khusus</p>
                    </div>

                    <!-- Content -->
                    <div class="col-span-2">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Isi Pengumuman <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <textarea name="content" id="content" rows="6" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 @error('content') border-red-300 @enderror" required>{{ old('content') }}</textarea>
                        </div>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">Tuliskan isi pengumuman dengan jelas dan lengkap</p>
                    </div>

                    <!-- Attachment -->
                    <div class="col-span-2">
                        <label for="attachment" class="block text-sm font-medium text-gray-700 mb-1">Lampiran (opsional)</label>
                        <div class="mt-1 flex items-center">
                            <div class="flex-grow relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-paperclip text-gray-400"></i>
                                </div>
                                <input type="file" name="attachment" id="attachment" class="pl-10 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 @error('attachment') border-red-300 @enderror">
                            </div>
                        </div>
                        @error('attachment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG (max 10MB)</p>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('guru.announcements.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all shadow-md hover:shadow-lg">
                        <i class="fas fa-paper-plane mr-2"></i> Publikasikan Pengumuman
                    </button>
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

    /* Custom toggle switch styling */
    .form-switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 20px;
    }
    .form-switch-checkbox {
        height: 0;
        width: 0;
        visibility: hidden;
        position: absolute;
    }
    .form-switch-label {
        display: block;
        overflow: hidden;
        cursor: pointer;
        background-color: #e5e7eb;
        border-radius: 20px;
        width: 100%;
        height: 100%;
        position: relative;
        transition: background-color 0.2s ease;
    }
    .form-switch-label:before {
        content: "";
        position: absolute;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        top: 2px;
        left: 2px;
        background: #fff;
        box-shadow: 0 0 2px 0 rgba(10, 10, 10, 0.3);
        transition: 0.2s;
    }
    .form-switch-checkbox:checked + .form-switch-label {
        background-color: #ef4444;
    }
    .form-switch-checkbox:checked + .form-switch-label:before {
        transform: translateX(20px);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('title');
        const contentTextarea = document.getElementById('content');
        const attachmentInput = document.getElementById('attachment');
        
        // Auto focus the title input when the form loads
        titleInput.focus();
        
        // Character counter for content
        contentTextarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            const maxLength = 5000; // Adjust this as needed
            const remainingChars = maxLength - currentLength;
            
            const counterEl = document.getElementById('content-counter');
            if (!counterEl) {
                const counter = document.createElement('div');
                counter.id = 'content-counter';
                counter.className = 'text-xs text-gray-500 text-right mt-1';
                counter.textContent = `${currentLength}/${maxLength} karakter`;
                this.parentNode.appendChild(counter);
            } else {
                counterEl.textContent = `${currentLength}/${maxLength} karakter`;
                
                if (remainingChars < 0) {
                    counterEl.classList.add('text-red-500');
                } else {
                    counterEl.classList.remove('text-red-500');
                }
            }
        });
        
        // Trigger the input event to initialize the counter
        if (contentTextarea.value) {
            contentTextarea.dispatchEvent(new Event('input'));
        }
        
        // File upload preview
        if (attachmentInput) {
            attachmentInput.addEventListener('change', function() {
                const filePreviewContainer = document.getElementById('file-preview-container');
                
                // Create preview container if it doesn't exist
                if (!filePreviewContainer) {
                    const container = document.createElement('div');
                    container.id = 'file-preview-container';
                    container.className = 'mt-3 p-3 bg-gray-50 border border-gray-200 rounded-lg';
                    this.parentNode.parentNode.appendChild(container);
                }
                
                const previewContainer = document.getElementById('file-preview-container');
                
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
                    const fileName = file.name;
                    const fileType = file.type;
                    
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
                    }
                    
                    previewContainer.innerHTML = `
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-2 rounded-lg bg-gray-100">
                                <i class="fas ${iconClass} ${colorClass} text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-700">${fileName}</p>
                                <p class="text-xs text-gray-500">${fileSize} MB</p>
                            </div>
                            <button type="button" id="remove-file" class="ml-auto text-gray-400 hover:text-red-500">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                    
                    // Add event listener to remove button
                    document.getElementById('remove-file').addEventListener('click', function() {
                        attachmentInput.value = '';
                        previewContainer.innerHTML = '';
                    });
                } else {
                    previewContainer.innerHTML = '';
                }
            });
        }
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(event) {
            let isValid = true;
            
            // Title validation
            if (titleInput.value.trim() === '') {
                showError(titleInput, 'Judul pengumuman harus diisi');
                isValid = false;
            } else if (titleInput.value.length > 255) {
                showError(titleInput, 'Judul pengumuman maksimal 255 karakter');
                isValid = false;
            } else {
                clearError(titleInput);
            }
            
            // Content validation
            if (contentTextarea.value.trim() === '') {
                showError(contentTextarea, 'Isi pengumuman harus diisi');
                isValid = false;
            } else {
                clearError(contentTextarea);
            }
            
            // Attachment validation
            if (attachmentInput.files.length > 0) {
                const file = attachmentInput.files[0];
                const fileSize = file.size / 1024 / 1024; // Convert to MB
                
                if (fileSize > 10) {
                    showError(attachmentInput, 'Ukuran file maksimal 10MB');
                    isValid = false;
                } else {
                    clearError(attachmentInput);
                }
            }
            
            if (!isValid) {
                event.preventDefault();
            }
        });
        
        function showError(element, message) {
            // Clear existing error message
            clearError(element);
            
            // Create error message
            const errorMessage = document.createElement('p');
            errorMessage.className = 'text-red-500 text-xs mt-1 error-message';
            errorMessage.textContent = message;
            
            // Add red border to element
            element.classList.add('border-red-300');
            
            // Add error message after element's parent div
            element.parentNode.parentNode.appendChild(errorMessage);
        }
        
        function clearError(element) {
            // Remove red border
            element.classList.remove('border-red-300');
            
            // Remove error message if exists
            const parent = element.parentNode.parentNode;
            const errorMessage = parent.querySelector('.error-message');
            if (errorMessage) {
                parent.removeChild(errorMessage);
            }
        }
    });
</script>
@endpush
