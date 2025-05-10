@extends('layouts.dashboard')

@section('title', 'Edit Pengumuman')

@section('header', 'Edit Pengumuman')

@section('navigation')
    <li>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-tachometer-alt text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.users.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-users text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Pengguna</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.subjects.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-book text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Mata Pelajaran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.classrooms.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-school text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Kelas</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.announcements.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-3 text-white">
            <i class="fas fa-bullhorn text-lg w-6"></i>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.profile.edit') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-user-circle text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Profil Saya</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.settings.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-cog text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Pengaturan</span>
        </a>
    </li>
@endsection

@section('content')
    <!-- Header with animation -->
    <div class="bg-gradient-to-r from-red-500 to-orange-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-edit text-9xl"></i>
        </div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Edit Pengumuman</h2>
            <p class="text-red-100">Perbarui informasi pengumuman untuk guru dan siswa.</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 transform transition hover:shadow-md">
        <div class="p-6">
            <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data" class="animate-fade-in">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group mb-5 md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Pengumuman</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-heading text-gray-400"></i>
                            </div>
                            <input type="text" name="title" id="title" value="{{ old('title', $announcement->title) }}" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-shadow duration-300" required>
                        </div>
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5 md:col-span-2">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Isi Pengumuman</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <textarea name="content" id="content" rows="6" 
                                class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-shadow duration-300" required>{{ old('content', $announcement->content) }}</textarea>
                        </div>
                        @error('content')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5">
                        <label for="audience" class="block text-sm font-medium text-gray-700 mb-1">Ditujukan Kepada</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-users text-gray-400"></i>
                            </div>
                            <select name="audience" id="audience" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-shadow duration-300" required>
                                <option value="all" {{ old('audience', $announcement->audience) == 'all' ? 'selected' : '' }}>Semua</option>
                                <option value="teachers" {{ old('audience', $announcement->audience) == 'teachers' ? 'selected' : '' }}>Guru Saja</option>
                                <option value="students" {{ old('audience', $announcement->audience) == 'students' ? 'selected' : '' }}>Siswa Saja</option>
                            </select>
                        </div>
                        @error('audience')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5">
                        <label for="is_important" class="block text-sm font-medium text-gray-700 mb-1">Pengumuman Penting</label>
                        <div class="mt-1 relative flex items-center">
                            <div class="flex items-center h-5">
                                <input id="is_important" name="is_important" type="checkbox" value="1" {{ old('is_important', $announcement->is_important) ? 'checked' : '' }}
                                    class="h-5 w-5 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_important" class="font-medium text-gray-700">Tandai sebagai pengumuman penting</label>
                                <p class="text-gray-500">Pengumuman penting akan ditampilkan dengan highlight khusus</p>
                            </div>
                        </div>
                        @error('is_important')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5">
                        <label for="publish_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publikasi</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                            <input type="datetime-local" name="publish_date" id="publish_date" 
                                value="{{ old('publish_date', $announcement->publish_date ? $announcement->publish_date->format('Y-m-d\TH:i') : '') }}" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-shadow duration-300">
                            <p class="text-xs text-gray-500 mt-1">Kosongkan untuk publikasi langsung</p>
                        </div>
                        @error('publish_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5">
                        <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kedaluwarsa</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar-times text-gray-400"></i>
                            </div>
                            <input type="datetime-local" name="expiry_date" id="expiry_date" 
                                value="{{ old('expiry_date', $announcement->expiry_date ? $announcement->expiry_date->format('Y-m-d\TH:i') : '') }}" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-shadow duration-300">
                            <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak memiliki batas waktu</p>
                        </div>
                        @error('expiry_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5 md:col-span-2">
                        <label for="attachment" class="block text-sm font-medium text-gray-700 mb-1">Lampiran (Opsional)</label>
                        <div class="mt-1 relative">
                            @if($announcement->attachment)
                                <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200 flex items-center">
                                    <div class="h-10 w-10 rounded-lg bg-red-100 flex items-center justify-center text-red-500">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-800">Lampiran saat ini</p>
                                        <p class="text-xs text-gray-500">{{ basename($announcement->attachment) }}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.announcements.download', $announcement) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-download"></i> Unduh
                                        </a>
                                        
                                        @php
                                            $extension = pathinfo($announcement->attachment, PATHINFO_EXTENSION);
                                        @endphp
                                        
                                        @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'pdf']))
                                        <a href="{{ Storage::url($announcement->attachment) }}" target="_blank" class="text-sm text-green-600 hover:text-green-800">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="remove_attachment" id="remove_attachment" value="1" 
                                            class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                        <label for="remove_attachment" class="ml-2 block text-sm text-gray-700">Hapus lampiran saat ini</label>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-paperclip text-gray-400 text-2xl" id="attachment-icon"></i>
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="attachment" id="attachment"
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-shadow duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                                    <p class="text-xs text-gray-500 mt-1">Maksimal 20MB. Format: pdf, doc, docx, xls, xlsx, ppt, pptx, jpg, png, zip</p>
                                    <p class="text-xs text-gray-500">Kosongkan jika tidak ingin mengubah lampiran</p>
                                </div>
                            </div>
                        </div>
                        @error('attachment')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="border-t border-gray-200 mt-8 pt-5">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Dibuat oleh: {{ $announcement->author->name }} pada {{ $announcement->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.announcements.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-300">
                                <i class="fas fa-times mr-2"></i> Batal
                            </a>
                            <button type="submit" class="ml-3 px-6 py-2 bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-lg hover:from-red-700 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>
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
        color: #ef4444;
    }
    
    .form-group:focus-within i {
        color: #ef4444;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize WYSIWYG editor if needed
        // Example: ClassicEditor.create(document.querySelector('#content'));
        
        // Animate form groups when focused
        document.querySelectorAll('.form-group input, .form-group select, .form-group textarea').forEach(element => {
            element.addEventListener('focus', function() {
                this.closest('.form-group').classList.add('focused');
            });
            
            element.addEventListener('blur', function() {
                this.closest('.form-group').classList.remove('focused');
            });
        });
        
        // Update attachment icon when file is selected
        document.getElementById('attachment').addEventListener('change', function() {
            const icon = document.getElementById('attachment-icon');
            if (this.files && this.files[0]) {
                const fileType = this.files[0].type;
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
            }
        });
    });
</script>
@endpush
