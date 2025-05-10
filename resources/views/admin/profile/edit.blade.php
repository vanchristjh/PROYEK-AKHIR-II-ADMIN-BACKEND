@extends('layouts.dashboard')

@section('title', 'Edit Profil')

@section('header', 'Edit Profil')

@section('content')
<div class="container-fluid">
    <div class="max-w-5xl mx-auto">
        @if (session('success'))
            <div class="alert-container animate-fade-in" style="animation-delay: 0.1s">
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                        <div class="ml-auto">
                            <button type="button" class="text-green-600 hover:text-green-800" onclick="this.parentElement.parentElement.parentElement.style.display='none'">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 animate-fade-in">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="mr-4">
                            <i class="fas fa-user-edit text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">Edit Informasi Profil</h2>
                            <p class="text-indigo-200 text-sm">Perbarui data profil dan informasi akun Anda</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.profile.show') }}" class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white rounded-lg transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Profil
                    </a>
                </div>
            </div>

            <div class="p-6 md:p-8">
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Avatar Upload Section -->
                        <div class="md:col-span-1 animate-fade-in" style="animation-delay: 0.2s">
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                                    <i class="fas fa-image text-indigo-500 mr-2"></i>
                                    Foto Profil
                                </h3>
                                
                                <div class="flex flex-col items-center space-y-4">
                                    <div class="avatar-upload-preview relative group">
                                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-lg mx-auto">
                                            <img id="avatar-preview" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/img/default-avatar.png') }}" 
                                                alt="Preview" class="w-full h-full object-cover">
                                        </div>
                                        
                                        <div class="absolute inset-0 bg-black/40 rounded-full opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity duration-300">
                                            <span class="text-white text-sm">
                                                <i class="fas fa-camera"></i> Ubah Foto
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="w-full">
                                        <label for="avatar" class="sr-only">Pilih Foto</label>
                                        <input type="file" name="avatar" id="avatar" class="hidden" accept="image/*" onchange="previewAvatar()">
                                        <button type="button" onclick="document.getElementById('avatar').click()" 
                                            class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <i class="fas fa-upload mr-2"></i>
                                            Unggah Foto
                                        </button>
                                        
                                        <p class="mt-2 text-xs text-gray-500 text-center">
                                            Format: JPG, PNG, GIF (Maks. 1MB)
                                        </p>

                                        @error('avatar')
                                            <p class="mt-2 text-sm text-red-600">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Personal Information -->
                        <div class="md:col-span-2 space-y-6 animate-fade-in" style="animation-delay: 0.3s">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                                <i class="fas fa-user text-indigo-500 mr-2"></i>
                                Informasi Pribadi
                            </h3>
                            
                            <div class="space-y-4">
                                <div class="form-group">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                    <div class="relative rounded-md">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <input type="text" name="name" id="name" 
                                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-300 @enderror" 
                                               value="{{ old('name', $user->name) }}" required 
                                               placeholder="Masukkan nama lengkap Anda">
                                    </div>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <div class="relative rounded-md">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400"></i>
                                        </div>
                                        <input type="email" name="email" id="email" 
                                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('email') border-red-300 @enderror" 
                                               value="{{ old('email', $user->email) }}" required 
                                               placeholder="contoh@email.com">
                                    </div>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="pt-6 border-t border-gray-200 mt-6">
                                <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center" id="password">
                                    <i class="fas fa-lock text-indigo-500 mr-2"></i>
                                    Ubah Password
                                </h3>
                                
                                <div class="space-y-4">
                                    <div class="form-group">
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru <span class="text-gray-500 text-xs">(Opsional)</span></label>
                                        <div class="relative rounded-md">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-lock text-gray-400"></i>
                                            </div>
                                            <input type="password" name="password" id="password" 
                                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('password') border-red-300 @enderror" 
                                                   placeholder="Kosongkan jika tidak ingin mengubah">
                                        </div>
                                        @error('password')
                                            <p class="mt-1 text-sm text-red-600">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                                        <div class="relative rounded-md">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-lock text-gray-400"></i>
                                            </div>
                                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                                   placeholder="Masukkan ulang password baru">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end animate-fade-in" style="animation-delay: 0.4s">
                        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                            <a href="{{ route('admin.profile.show') }}" class="inline-flex justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                            <button type="submit" class="inline-flex justify-center px-6 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform hover:-translate-y-0.5 transition-all duration-200">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Animation effects */
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
    
    .animate-fade-in {
        animation: fade-in 0.5s ease-in-out forwards;
    }
    
    /* Form focus effects */
    .form-group:focus-within label {
        color: #4f46e5; /* indigo-600 */
    }
    
    .form-group:focus-within i {
        color: #4f46e5; /* indigo-600 */
    }
    
    /* Avatar upload hover effect */
    .avatar-upload-preview {
        cursor: pointer;
    }
    
    /* Custom checkbox styles */
    .custom-checkbox {
        appearance: none;
        width: 1.25rem;
        height: 1.25rem;
        border: 2px solid #d1d5db; /* gray-300 */
        border-radius: 0.25rem;
        background-color: white;
        outline: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .custom-checkbox:checked {
        background-color: #4f46e5; /* indigo-600 */
        border-color: #4f46e5; /* indigo-600 */
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23ffffff'%3E%3Cpath fill-rule='evenodd' d='M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z' clip-rule='evenodd' /%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 0.75rem;
    }
    
    /* Custom file input */
    .custom-file-input::-webkit-file-upload-button {
        visibility: hidden;
    }
    
    .custom-file-input::before {
        content: 'Pilih File';
        display: inline-block;
        background: linear-gradient(to bottom, #ffffff, #f9fafb);
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        outline: none;
        white-space: nowrap;
        -webkit-user-select: none;
        cursor: pointer;
        font-weight: 500;
        font-size: 0.875rem;
        color: #374151;
    }
    
    .custom-file-input:hover::before {
        border-color: #9ca3af;
    }
    
    .custom-file-input:active::before {
        background: #f3f4f6;
    }
</style>
@endpush

@push('scripts')
<script>
    function previewAvatar() {
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.getElementById('avatar-preview');
        const defaultAvatar = "{{ asset('assets/img/default-avatar.png') }}";
        
        if (avatarInput.files && avatarInput.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
                
                // Add a small animation effect
                avatarPreview.style.transform = 'scale(0.9)';
                avatarPreview.style.transition = 'transform 0.3s';
                
                setTimeout(() => {
                    avatarPreview.style.transform = 'scale(1)';
                }, 150);
            }
            
            reader.readAsDataURL(avatarInput.files[0]);
        } else {
            // If user cancels file selection, restore original avatar or default
            const currentUserAvatar = "{{ $user->avatar ? asset('storage/' . $user->avatar) : '' }}";
            avatarPreview.src = currentUserAvatar || defaultAvatar;
        }
    }
    
    // Initialize any interactive elements
    document.addEventListener('DOMContentLoaded', function() {
        // Add focus effects to form groups
        const formGroups = document.querySelectorAll('.form-group');
        formGroups.forEach(group => {
            const input = group.querySelector('input');
            if (input) {
                input.addEventListener('focus', () => {
                    group.classList.add('focused');
                });
                
                input.addEventListener('blur', () => {
                    group.classList.remove('focused');
                });
            }
        });
        
        // Make avatar preview clickable to trigger file input
        const avatarPreview = document.querySelector('.avatar-upload-preview');
        const avatarInput = document.getElementById('avatar');
        
        if (avatarPreview && avatarInput) {
            avatarPreview.addEventListener('click', () => {
                avatarInput.click();
            });
        }
    });
</script>
@endpush
