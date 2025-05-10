@extends('layouts.dashboard')

@section('title', 'Edit Pengguna')

@section('header', 'Edit Pengguna')

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
    <!-- Header dengan animasi -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-user-edit text-9xl"></i>
        </div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Edit Pengguna: {{ $user->name }}</h2>
            <p class="text-purple-100">Perbarui informasi dan pengaturan akun pengguna.</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100/50 transform transition hover:shadow-xl">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-0">
            <!-- Sidebar dengan info pengguna -->
            <div class="bg-gradient-to-b from-purple-600 to-indigo-700 text-white p-6 relative overflow-hidden">
                <div class="relative z-10">
                    <div class="text-center mb-6">
                        <div class="avatar-preview-container mx-auto mb-4">
                            <img id="avatar-preview" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/img/default-avatar.png') }}" alt="Avatar Preview" class="rounded-full border-4 border-white/30 shadow-lg mx-auto" style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                        <h5 class="font-bold text-xl mb-1">{{ $user->name }}</h5>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/20 backdrop-blur-sm">
                            {{ $user->role->name }}
                        </span>
                    </div>
                    
                    <div class="user-info-list space-y-4 mt-6">
                        <div class="flex items-center animate-fade-in-up" style="animation-delay: 0.3s">
                            <div class="icon-box bg-white/10 rounded-lg p-2 mr-3">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <span class="text-white/60 text-xs">Email</span>
                                <p class="text-sm">{{ $user->email }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center animate-fade-in-up" style="animation-delay: 0.4s">
                            <div class="icon-box bg-white/10 rounded-lg p-2 mr-3">
                                <i class="fas fa-at"></i>
                            </div>
                            <div>
                                <span class="text-white/60 text-xs">Username</span>
                                <p class="text-sm">{{ $user->username }}</p>
                            </div>
                        </div>
                        
                        @if($user->classroom)
                        <div class="flex items-center animate-fade-in-up" style="animation-delay: 0.5s">
                            <div class="icon-box bg-white/10 rounded-lg p-2 mr-3">
                                <i class="fas fa-school"></i>
                            </div>
                            <div>
                                <span class="text-white/60 text-xs">Kelas</span>
                                <p class="text-sm">{{ $user->classroom->name }}</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="mt-8 pt-6 border-t border-white/10">
                        <div class="flex items-center mb-4 animate-fade-in-up" style="animation-delay: 0.6s">
                            <div class="icon-box bg-white/10 rounded-lg p-2 mr-3">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <span class="text-white/60 text-xs">Bergabung pada</span>
                                <p class="text-sm">{{ $user->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center animate-fade-in-up" style="animation-delay: 0.7s">
                            <div class="icon-box bg-white/10 rounded-lg p-2 mr-3">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div>
                                <span class="text-white/60 text-xs">Terakhir diperbarui</span>
                                <p class="text-sm">{{ $user->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Floating particles -->
                <div class="particles-container absolute inset-0 w-full h-full overflow-hidden">
                    @for ($i = 0; $i < 8; $i++)
                        <div class="absolute rounded-full bg-white/10" 
                            style="width: {{ rand(4, 15) }}px; height: {{ rand(4, 15) }}px; 
                                left: {{ rand(10, 90) }}%; top: {{ rand(10, 90) }}%;
                                animation: floating {{ rand(15, 30) }}s infinite ease-in-out alternate;">
                        </div>
                    @endfor
                </div>
            </div>
            
            <!-- Form Column -->
            <div class="col-span-3 p-6">
                <h4 class="font-semibold mb-4 text-gray-800 border-b border-gray-200 pb-3">
                    <i class="fas fa-user-edit mr-2 text-purple-600"></i>
                    Edit Detail Pengguna
                </h4>
                
                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md animate-fade-in">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="animate-fade-in">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group mb-5">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                    class="pl-10 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-300" required>
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
                                <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" 
                                    class="pl-10 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-300" required>
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
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                    class="pl-10 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-300" required>
                            </div>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group mb-5">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password" name="password" id="password" 
                                    class="pl-10 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-300">
                                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fas fa-eye text-gray-400"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group mb-5">
                            <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">Peran Pengguna</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user-tag text-gray-400"></i>
                                </div>
                                <select name="role_id" id="role_id" 
                                    class="pl-10 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-300" required>
                                    <option value="" disabled {{ old('role_id', $user->role_id) ? '' : 'selected' }}>Pilih peran...</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('role_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group mb-5" id="classroom-container" style="{{ old('role_id', $user->role_id) == 3 ? 'display: block;' : 'display: none;' }}">
                            <label for="classroom_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas (untuk Siswa)</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-school text-gray-400"></i>
                                </div>
                                <select name="classroom_id" id="classroom_id" 
                                    class="pl-10 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-300">
                                    <option value="" {{ !old('classroom_id', $user->classroom_id) ? 'selected' : '' }}>Pilih kelas...</option>
                                    @foreach($classrooms as $classroom)
                                        <option value="{{ $classroom->id }}" {{ old('classroom_id', $user->classroom_id) == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('classroom_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group mb-5">
                            <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                            <div class="mt-1 flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <img id="avatar-small-preview" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/img/default-avatar.png') }}" alt="Avatar Preview" class="rounded-full border border-2 border-gray-200 shadow-sm" style="width: 64px; height: 64px; object-fit: cover;">
                                </div>
                                <div class="flex-grow">
                                    <input type="file" name="avatar" id="avatar" accept="image/*"
                                        class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                </div>
                                <button type="button" id="resetAvatar" class="p-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 transition-all duration-200 transform hover:-translate-y-1" title="Reset avatar">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Opsional. Format: JPG, PNG, GIF. Maks: 1MB.</p>
                            @error('avatar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="border-t border-gray-200 mt-8 pt-5">
                        <div class="flex justify-between items-center">
                            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>
                            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-indigo-700 text-white rounded-lg hover:from-purple-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 flex items-center">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .animate-fade-in {
        animation: fade-in 0.6s ease-in-out;
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 0.5s ease-out forwards;
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
    
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .form-group:focus-within label {
        color: #8b5cf6;
    }
    
    .form-group:focus-within i {
        color: #8b5cf6;
    }
    
    .icon-box {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    @keyframes floating {
        0%, 100% {
            transform: translate(0, 0);
        }
        50% {
            transform: translate(5px, -5px);
        }
    }
    
    .highlight-avatar {
        animation: highlight 0.5s ease;
    }
    
    @keyframes highlight {
        0% {
            box-shadow: 0 0 0 0 rgba(139, 92, 246, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(139, 92, 246, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(139, 92, 246, 0);
        }
    }
    
    .avatar-preview-container img:hover {
        transform: scale(1.05);
        transition: all 0.2s ease-in-out;
    }
    
    /* animated background gradient */
    .bg-gradient-to-r {
        background-size: 200% 200%;
        animation: gradient-animation 15s ease infinite;
    }
    
    @keyframes gradient-animation {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Role and classroom management
        const roleSelect = document.getElementById('role_id');
        const classroomContainer = document.getElementById('classroom-container');
        const classroomSelect = document.getElementById('classroom_id');

        function toggleClassroomField() {
            if (roleSelect.value == '3') {
                classroomContainer.style.display = 'block';
                classroomSelect.required = true;
                classroomContainer.classList.add('animate-fade-in');
            } else {
                classroomContainer.style.display = 'none';
                classroomSelect.required = false;
                classroomSelect.value = '';
                classroomContainer.classList.remove('animate-fade-in');
            }
        }
        
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
        
        // Reset avatar button
        const resetAvatarBtn = document.getElementById('resetAvatar');
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.getElementById('avatar-preview');
        const avatarSmallPreview = document.getElementById('avatar-small-preview');
        
        resetAvatarBtn.addEventListener('click', function() {
            avatarInput.value = '';
            const defaultAvatar = "{{ asset('assets/img/default-avatar.png') }}";
            const currentUserAvatar = "{{ $user->avatar ? asset('storage/' . $user->avatar) : '' }}";
            avatarPreview.src = currentUserAvatar || defaultAvatar;
            avatarSmallPreview.src = currentUserAvatar || defaultAvatar;
            
            // Add reset animation
            avatarPreview.classList.add('animate-fade-in');
            avatarSmallPreview.classList.add('animate-fade-in');
            setTimeout(() => {
                avatarPreview.classList.remove('animate-fade-in');
                avatarSmallPreview.classList.remove('animate-fade-in');
            }, 600);
        });
        
        // Preview avatar
        avatarInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                    avatarSmallPreview.src = e.target.result;
                    
                    // Add highlight effect
                    avatarPreview.classList.add('highlight-avatar');
                    avatarSmallPreview.classList.add('highlight-avatar');
                    setTimeout(() => {
                        avatarPreview.classList.remove('highlight-avatar');
                        avatarSmallPreview.classList.remove('highlight-avatar');
                    }, 700);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        roleSelect.addEventListener('change', toggleClassroomField);
        toggleClassroomField(); // Initial check on page load
        
        // Animate form fields on focus
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
        
        // Auto-hide success alert after 5 seconds
        const successAlert = document.querySelector('.bg-green-50');
        if (successAlert) {
            setTimeout(() => {
                successAlert.classList.add('opacity-0');
                setTimeout(() => {
                    successAlert.style.display = 'none';
                }, 300);
            }, 5000);
            successAlert.classList.add('transition-opacity', 'duration-300');
        }
        
        // Button hover effect
        const submitButton = document.querySelector('button[type="submit"]');
        submitButton.addEventListener('mouseover', function() {
            this.classList.add('pulse-button');
        });
        submitButton.addEventListener('mouseout', function() {
            this.classList.remove('pulse-button');
        });
    });
    
    function previewAvatar() {
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.getElementById('avatar-preview');
        const avatarSmallPreview = document.getElementById('avatar-small-preview');
        const defaultAvatar = "{{ asset('assets/img/default-avatar.png') }}";

        if (avatarInput.files && avatarInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
                avatarSmallPreview.src = e.target.result;
            }
            reader.readAsDataURL(avatarInput.files[0]);
        } else {
            const currentUserAvatar = "{{ $user->avatar ? asset('storage/' . $user->avatar) : '' }}";
            avatarPreview.src = currentUserAvatar || defaultAvatar;
            avatarSmallPreview.src = currentUserAvatar || defaultAvatar;
        }
    }
</script>
@endpush