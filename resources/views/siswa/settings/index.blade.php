@extends('layouts.dashboard')

@section('title', 'Pengaturan Akun')

@section('header', 'Pengaturan')

@section('content')
<div class="max-w-4xl mx-auto">
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-lg font-medium text-gray-800">Pengaturan Akun</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('siswa.settings.update') }}" method="POST">
                @csrf
                
                <!-- Email Field -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                    <input type="email" name="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password Change Section -->
                <div class="mb-6 border-t border-gray-200 pt-6">
                    <h3 class="text-md font-medium text-gray-700 mb-4">Ubah Password</h3>
                    
                    <div class="mb-4">
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input type="password" name="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                
                <!-- Appearance Settings -->
                <div class="mb-6 border-t border-gray-200 pt-6">
                    <h3 class="text-md font-medium text-gray-700 mb-4">Tampilan</h3>
                    
                    <div class="mb-4">
                        <label for="theme" class="block text-sm font-medium text-gray-700 mb-1">Tema</label>
                        <select name="theme" id="theme" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="light" {{ isset($user->preferences['theme']) && $user->preferences['theme'] == 'light' ? 'selected' : '' }}>Light</option>
                            <option value="dark" {{ isset($user->preferences['theme']) && $user->preferences['theme'] == 'dark' ? 'selected' : '' }}>Dark</option>
                            <option value="system" {{ isset($user->preferences['theme']) && $user->preferences['theme'] == 'system' ? 'selected' : '' }}>System (Auto)</option>
                        </select>
                    </div>
                </div>
                
                <!-- Notification Settings -->
                <div class="mb-6 border-t border-gray-200 pt-6">
                    <h3 class="text-md font-medium text-gray-700 mb-4">Notifikasi</h3>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="notifications_enabled" id="notifications_enabled" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ isset($user->preferences['notifications_enabled']) && $user->preferences['notifications_enabled'] ? 'checked' : '' }}>
                        <label for="notifications_enabled" class="ml-2 block text-sm text-gray-700">
                            Aktifkan notifikasi email
                        </label>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
