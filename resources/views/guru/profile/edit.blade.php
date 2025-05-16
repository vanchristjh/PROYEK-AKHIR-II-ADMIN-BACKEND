@extends('layouts.guru')

@section('title', 'Edit Profil')

@push('styles')
<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out forwards;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .gradient-text {
        background: linear-gradient(90deg, #3b82f6, #4f46e5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endpush

@section('content')
<div class="container px-6 py-8 mx-auto">
    <!-- Header Section -->
    <div class="mb-6 animate-fade-in" style="animation-delay: 0.1s">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 gradient-text">
                    Edit Profil
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Perbarui informasi profil dan email Anda
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('guru.profile.show') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-gray-700 text-sm font-medium inline-flex items-center transition-colors shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Profil
                </a>
            </div>
        </div>
    </div>

    <!-- Profile Edit Form -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden mb-6 animate-fade-in" style="animation-delay: 0.2s">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold gradient-text flex items-center">
                <i class="fas fa-user-edit mr-2"></i>
                Informasi Profil
            </h3>
            <p class="text-sm text-gray-500 mt-1">Perbarui informasi profil dan foto Anda</p>
        </div>
        
        <form action="{{ route('guru.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="p-6">
                @if(session('status') === 'profile-updated')
                    <div class="mb-6 p-4 text-sm rounded-md bg-green-100 text-green-700">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Profil berhasil diperbarui.
                        </div>
                    </div>
                @endif
                
                <div class="flex flex-col md:flex-row">
                    <!-- Avatar Section -->
                    <div class="flex-shrink-0 mb-6 md:mb-0 md:mr-8">
                        <div class="flex flex-col items-center">
                            <div class="w-32 h-32 relative mb-4">
                                @if($user->avatar)
                                    <img id="avatar-preview" class="w-full h-full object-cover rounded-full border-4 border-white shadow-md" 
                                         src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                                @else
                                    <div id="avatar-preview" class="w-full h-full bg-gradient-to-br from-indigo-500 to-blue-600 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-md">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                
                                <div class="absolute bottom-0 right-0 -mb-1 -mr-1">
                                    <label for="avatar" class="w-10 h-10 bg-indigo-600 hover:bg-indigo-700 rounded-full flex items-center justify-center text-white cursor-pointer shadow-md transition-colors">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                    <input type="file" name="avatar" id="avatar" class="hidden" accept="image/*" onchange="previewImage()">
                                </div>
                            </div>
                            
                            <span class="text-sm text-gray-500">Klik ikon kamera untuk mengganti foto</span>
                            
                            @error('avatar')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Profile Info Section -->
                    <div class="flex-grow space-y-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                       class="pl-10 w-full rounded-lg shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                       class="pl-10 w-full rounded-lg shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-at text-gray-400"></i>
                                </div>
                                <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" 
                                       class="pl-10 w-full rounded-lg bg-gray-50 shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Username tidak dapat diubah</p>
                        </div>
                        
                        @if(isset($user->teacher) && $user->teacher)
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-gray-400"></i>
                                    </div>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->teacher->phone ?? '') }}" 
                                        class="pl-10 w-full rounded-lg shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end space-x-2">
                <a href="{{ route('guru.profile.show') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg shadow-sm transition-colors">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function previewImage() {
        const input = document.getElementById('avatar');
        const preview = document.getElementById('avatar-preview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    // Replace div with img
                    const img = document.createElement('img');
                    img.id = 'avatar-preview';
                    img.classList = 'w-full h-full object-cover rounded-full border-4 border-white shadow-md';
                    img.src = e.target.result;
                    preview.parentNode.replaceChild(img, preview);
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
