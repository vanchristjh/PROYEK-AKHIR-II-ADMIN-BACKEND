@extends('layouts.dashboard')

@section('title', 'Edit Mata Pelajaran')

@section('header', 'Edit Mata Pelajaran')

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
        <a href="{{ route('admin.subjects.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-3 text-white">
            <i class="fas fa-book text-lg w-6"></i>
            <span class="ml-3">Mata Pelajaran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.classrooms.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-school text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Kelas</span>
        </a>
    </li>
@endsection

@section('content')
    <!-- Header with animation -->
    <div class="bg-gradient-to-r from-amber-500 to-amber-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-edit text-9xl"></i>
        </div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Edit Mata Pelajaran</h2>
            <p class="text-amber-100">Perbarui informasi mata pelajaran dan tetapkan guru pengajar.</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 transform transition hover:shadow-md">
        <div class="p-6">
            <form action="{{ route('admin.subjects.update', $subject) }}" method="POST" class="animate-fade-in">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group mb-5">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Mata Pelajaran</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-book-open text-gray-400"></i>
                            </div>
                            <input type="text" name="name" id="name" value="{{ old('name', $subject->name) }}" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50 transition-shadow duration-300" required>
                        </div>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5">
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Kode Mata Pelajaran</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-hashtag text-gray-400"></i>
                            </div>
                            <input type="text" name="code" id="code" value="{{ old('code', $subject->code) }}" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50 transition-shadow duration-300" required>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Kode unik untuk mata pelajaran ini (maksimal 10 karakter)</p>
                        @error('code')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5 md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                <i class="fas fa-align-left text-gray-400"></i>
                            </div>
                            <textarea name="description" id="description" rows="4" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50 transition-shadow duration-300">{{ old('description', $subject->description) }}</textarea>
                        </div>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Guru Pengajar</label>
                        <div class="mt-1 bg-amber-50 p-5 rounded-xl border border-amber-100 max-h-64 overflow-y-auto">
                            @if($teachers->isNotEmpty())
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($teachers as $teacher)
                                        <div class="flex items-center p-2 rounded-lg hover:bg-amber-100/50 transition-colors">
                                            <input type="checkbox" id="teacher-{{ $teacher->id }}" name="teachers[]" value="{{ $teacher->id }}" 
                                                class="rounded border-amber-300 text-amber-600 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50"
                                                {{ in_array($teacher->id, $assignedTeachers) ? 'checked' : '' }}>
                                            <label for="teacher-{{ $teacher->id }}" class="ml-2 flex items-center cursor-pointer">
                                                <div class="w-8 h-8 rounded-full bg-amber-200 flex items-center justify-center text-amber-700 mr-2">
                                                    {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-700">{{ $teacher->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $teacher->email }}</p>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex items-center justify-center py-8 text-center">
                                    <div>
                                        <div class="text-amber-400 text-3xl mb-2">
                                            <i class="fas fa-exclamation-circle"></i>
                                        </div>
                                        <p class="text-gray-500">Tidak ada guru tersedia. Silakan buat akun guru terlebih dahulu.</p>
                                        <a href="{{ route('admin.users.create') }}" class="text-amber-600 hover:text-amber-800 text-sm mt-2 inline-block">
                                            <i class="fas fa-plus-circle mr-1"></i> Tambah guru baru
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @error('teachers')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="border-t border-gray-200 mt-8 pt-5">
                    <div class="flex justify-end">
                        <a href="{{ route('admin.subjects.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-300">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                        <button type="submit" class="ml-3 px-6 py-2 bg-gradient-to-r from-amber-600 to-amber-700 text-white rounded-lg hover:from-amber-700 hover:to-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
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
        color: #d97706;
    }
    
    .form-group:focus-within i {
        color: #d97706;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate form groups when focused
        document.querySelectorAll('.form-group input, .form-group select, .form-group textarea').forEach(element => {
            element.addEventListener('focus', function() {
                this.closest('.form-group').classList.add('focused');
            });
            
            element.addEventListener('blur', function() {
                this.closest('.form-group').classList.remove('focused');
            });
        });
    });
</script>
@endpush
