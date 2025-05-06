@extends('layouts.dashboard')

@section('title', 'Buat Pengumuman Baru')

@section('header', 'Buat Pengumuman')

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
    <li>
        <a href="{{ route('guru.announcements.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-3 text-white">
            <i class="fas fa-bullhorn text-lg w-6"></i>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
@endsection

@section('content')
    <!-- Header with animation -->
    <div class="bg-gradient-to-r from-red-500 to-pink-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-bullhorn text-9xl"></i>
        </div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Buat Pengumuman Baru</h2>
            <p class="text-red-100">Buat dan bagikan pengumuman untuk komunitas sekolah.</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 transform transition hover:shadow-md">
        <div class="p-6">
            <form action="{{ route('guru.announcements.store') }}" method="POST" enctype="multipart/form-data" class="animate-fade-in">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group mb-5 md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Pengumuman</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-heading text-gray-400"></i>
                            </div>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-shadow duration-300" required>
                        </div>
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5 md:col-span-2">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Konten Pengumuman</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <textarea name="content" id="content" rows="6" 
                                class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-shadow duration-300" required>{{ old('content') }}</textarea>
                        </div>
                        @error('content')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5">
                        <label for="audience" class="block text-sm font-medium text-gray-700 mb-1">Ditujukan Untuk</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-users text-gray-400"></i>
                            </div>
                            <select name="audience" id="audience" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-shadow duration-300" required>
                                <option value="all" {{ old('audience') == 'all' ? 'selected' : '' }}>Semua</option>
                                <option value="students" {{ old('audience') == 'students' ? 'selected' : '' }}>Siswa</option>
                                <option value="teachers" {{ old('audience') == 'teachers' ? 'selected' : '' }}>Guru</option>
                            </select>
                        </div>
                        @error('audience')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5">
                        <label for="publish_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publikasi</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                            <input type="datetime-local" name="publish_date" id="publish_date" value="{{ old('publish_date') }}" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-shadow duration-300">
                            <p class="text-xs text-gray-500 mt-1">Kosongkan untuk mempublikasi langsung.</p>
                        </div>
                        @error('publish_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_important" id="is_important" value="1" {{ old('is_important') ? 'checked' : '' }} 
                                class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <label for="is_important" class="ml-2 block text-sm font-medium text-gray-700">Tandai sebagai Penting</label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 pl-6">Pengumuman penting akan ditampilkan dengan penanda khusus.</p>
                        @error('is_important')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5 md:col-span-2">
                        <label for="attachment" class="block text-sm font-medium text-gray-700 mb-1">Lampiran (Opsional)</label>
                        <div class="mt-1">
                            <div class="flex items-center justify-center w-full">
                                <label class="flex flex-col rounded-lg border-2 border-dashed w-full h-32 p-10 group text-center border-gray-300 cursor-pointer hover:bg-gray-50">
                                    <div class="h-full w-full text-center flex flex-col items-center justify-center">
                                        <div class="flex flex-auto max-h-48 mx-auto">
                                            <i class="fas fa-cloud-upload-alt text-gray-300 text-3xl"></i>
                                        </div>
                                        <p class="pointer-none text-gray-500 text-sm"><span class="text-red-600 hover:underline">Klik untuk mengunggah</span> atau seret dan lepas file di sini</p>
                                        <p class="text-xs text-gray-500 mt-1">Maksimal 10MB</p>
                                    </div>
                                    <input type="file" name="attachment" id="attachment" class="hidden">
                                </label>
                            </div>
                        </div>
                        <div id="file-selected" class="mt-2 text-sm text-gray-600 hidden">
                            <span class="font-medium">File terpilih:</span> <span id="file-name"></span>
                        </div>
                        @error('attachment')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="border-t border-gray-200 mt-8 pt-5">
                    <div class="flex justify-end">
                        <a href="{{ route('guru.announcements.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-300">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                        <button type="submit" class="ml-3 px-6 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-lg hover:from-red-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-bullhorn mr-2"></i> Publikasikan Pengumuman
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
        color: #EF4444;
    }
    
    .form-group:focus-within i {
        color: #EF4444;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle file selection display
        const attachmentInput = document.getElementById('attachment');
        const fileSelected = document.getElementById('file-selected');
        const fileName = document.getElementById('file-name');
        
        if (attachmentInput) {
            attachmentInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    fileName.textContent = this.files[0].name;
                    fileSelected.classList.remove('hidden');
                } else {
                    fileSelected.classList.add('hidden');
                }
            });
        }
    });
</script>
@endpush
