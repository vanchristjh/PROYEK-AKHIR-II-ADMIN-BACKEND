@extends('layouts.guru')

@section('title', 'Edit Profil')

@section('content')
<div class="container px-6 py-8 mx-auto">
    <!-- Header Section -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            Edit Profil
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Perbarui informasi profil dan email Anda
        </p>
    </div>

    <!-- Profile Edit Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
        <form action="{{ route('guru.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="p-6">
                @if(session('status') === 'profile-updated')
                    <div class="mb-4 p-4 text-sm rounded-md bg-green-100 text-green-700">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Profil berhasil diperbarui.
                        </div>
                    </div>
                @endif
                
                <div class="flex flex-col md:flex-row">
                    <!-- Avatar Section -->
                    <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-6">
                        <div class="flex flex-col items-center">
                            <div class="w-28 h-28 relative mb-3">
                                @if($user->avatar)
                                    <img id="avatar-preview" class="w-full h-full object-cover rounded-full border-4 border-white shadow-sm" 
                                         src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                                @else
                                    <div id="avatar-preview" class="w-full h-full bg-indigo-600 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-sm">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            
                            <label for="avatar" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md shadow-sm cursor-pointer transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Ubah Foto
                            </label>
                            <input type="file" name="avatar" id="avatar" class="hidden" accept="image/*" onchange="previewImage()">
                            
                            @error('avatar')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Profile Info Section -->
                    <div class="flex-grow">
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                       class="mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                       class="mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                                <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" 
                                       class="mt-1 w-full rounded-md bg-gray-100 shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                                <p class="mt-1 text-xs text-gray-500">Username tidak dapat diubah</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end space-x-2">
                <a href="{{ route('guru.profile.show') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-md shadow-sm transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md shadow-sm transition-colors">
                    Simpan Perubahan
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
                    img.classList = 'w-full h-full object-cover rounded-full border-4 border-white shadow-sm';
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
