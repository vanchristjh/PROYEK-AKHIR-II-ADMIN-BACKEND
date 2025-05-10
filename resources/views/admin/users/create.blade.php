@extends('layouts.dashboard')

@section('title', 'Tambah Pengguna Baru')

@section('header', 'Tambah Pengguna Baru')

@section('navigation')
    <li>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-tachometer-alt text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.users.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-3 text-white">
            <i class="fas fa-users text-lg w-6"></i>
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
        <a href="{{ route('admin.announcements.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-bullhorn text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Pengumuman</span>
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
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-user-plus text-9xl"></i>
        </div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Tambah Pengguna Baru</h2>
            <p class="text-blue-100">Buat akun baru untuk administrator, guru, atau siswa.</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 transform transition hover:shadow-md">
        <div class="p-6">
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="animate-fade-in">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group mb-5">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-shadow duration-300 form-control" required>
                        </div>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5">
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-at text-gray-400"></i>
                            </div>
                            <input type="text" name="username" id="username" value="{{ old('username') }}" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-shadow duration-300 form-control" required>
                        </div>
                        @error('username')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-shadow duration-300 form-control" required>
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" name="password" id="password" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-shadow duration-300 form-control" required>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5">
                        <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">Peran</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user-tag text-gray-400"></i>
                            </div>
                            <select name="role_id" id="role_id" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-shadow duration-300 form-control" required>
                                <option value="">Pilih Peran</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('role_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5" id="classroom-container" style="display: none;">
                        <label for="classroom_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-school text-gray-400"></i>
                            </div>
                            <select name="classroom_id" id="classroom_id" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-shadow duration-300 form-control">
                                <option value="">Pilih Kelas</option>
                                @foreach($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}" {{ old('classroom_id') == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('classroom_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-5">
                        <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">Avatar</label>
                        <div class="mt-1 relative">
                            <div class="flex items-center">
                                <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center mr-4 avatar-preview">
                                    <i class="fas fa-user text-gray-400 text-3xl" id="avatar-icon"></i>
                                    <img id="avatar-preview" class="w-full h-full object-cover rounded-full hidden">
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="avatar" id="avatar" accept="image/*" 
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-shadow duration-300 file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0 file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="text-xs text-gray-500 mt-1">Max 1MB. Format: jpg, png, gif</p>
                                </div>
                            </div>
                        </div>
                        @error('avatar')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="border-t border-gray-200 mt-8 pt-5">
                    <div class="flex justify-end">
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-300 btn-glass">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                        <button type="submit" class="ml-3 px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 animate-pulse">
                            <i class="fas fa-save mr-2"></i> Simpan Pengguna
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
        color: #3b82f6;
    }
    
    .form-group:focus-within i {
        color: #3b82f6;
    }

    .avatar-preview {
        transition: all 0.3s ease-in-out;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: 3px solid rgba(59, 130, 246, 0.1);
    }

    .btn-glass {
        background-color: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        transition: all 0.3s ease;
    }
    
    .btn-glass:hover {
        background-color: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.3);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    /* Enhanced Form Styling */
    input.form-control, select.form-control {
        transition: all 0.3s ease;
    }
    
    input.form-control:focus, select.form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        transform: translateY(-2px);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role_id');
        const classroomContainer = document.getElementById('classroom-container');
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.getElementById('avatar-preview');
        const avatarIcon = document.getElementById('avatar-icon');
        
        // Show/hide classroom field based on role selection (3 = siswa)
        function toggleClassroomField() {
            if (roleSelect.value == '3') {
                classroomContainer.style.display = 'block';
                classroomContainer.classList.add('animate-fade-in');
            } else {
                classroomContainer.style.display = 'none';
                classroomContainer.classList.remove('animate-fade-in');
            }
        }
        
        roleSelect.addEventListener('change', toggleClassroomField);
        
        // Avatar preview with enhanced effects
        avatarInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                    avatarPreview.classList.remove('hidden');
                    avatarIcon.classList.add('hidden');
                    
                    // Add a highlight effect
                    avatarPreview.classList.add('highlight');
                    setTimeout(() => {
                        avatarPreview.classList.remove('highlight');
                    }, 700);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        // Initial check on page load
        toggleClassroomField();
        
        // Animate form groups when focused with enhanced highlight
        document.querySelectorAll('.form-group input, .form-group select').forEach(element => {
            element.addEventListener('focus', function() {
                const formGroup = this.closest('.form-group');
                formGroup.classList.add('focused');
                formGroup.style.transition = 'all 0.3s ease';
                formGroup.style.transform = 'translateY(-2px)';
            });
            
            element.addEventListener('blur', function() {
                const formGroup = this.closest('.form-group');
                formGroup.classList.remove('focused');
                formGroup.style.transform = '';
            });
        });

        // Add pulse animation to submit button
        const submitButton = document.querySelector('button[type="submit"]');
        submitButton.addEventListener('mouseover', function() {
            this.classList.add('animate-pulse');
        });
        submitButton.addEventListener('mouseout', function() {
            this.classList.remove('animate-pulse');
        });

        // Enhance header animation
        const header = document.querySelector('.bg-gradient-to-r');
        if (header) {
            header.classList.add('animate-gradient-x');
            
            // Create floating particles effect in header
            for (let i = 0; i < 5; i++) {
                const particle = document.createElement('div');
                particle.className = 'absolute rounded-full bg-white/10';
                particle.style.width = (Math.random() * 20 + 5) + 'px';
                particle.style.height = particle.style.width;
                particle.style.left = (Math.random() * 100) + '%';
                particle.style.top = (Math.random() * 100) + '%';
                particle.style.animation = `float ${Math.random() * 3 + 2}s infinite ease-in-out alternate`;
                header.appendChild(particle);
            }
        }
    });
</script>
@endpush
