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
            <form action="{{ route('guru.materials.store') }}" method="POST" enctype="multipart/form-data" class="animate-fade-in">
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
    });
</script>
@endpush
