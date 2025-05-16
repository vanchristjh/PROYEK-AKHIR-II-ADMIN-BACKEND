@extends('layouts.dashboard')

@section('title', 'Tambah Mata Pelajaran')

@section('header', 'Tambah Mata Pelajaran')

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
    <li>
        <a href="{{ route('admin.announcements.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-bullhorn text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
@endsection

@section('content')
    <!-- Header with animation -->
    <div class="bg-gradient-to-r from-amber-500 to-amber-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-book text-9xl"></i>
        </div>
        <div class="relative">
            <h2 class="text-2xl font-bold mb-2">Tambah Mata Pelajaran Baru</h2>
            <p class="text-amber-100 mb-4">Isi detail mata pelajaran baru di bawah ini</p>
            <div class="flex items-center">
                <a href="{{ route('admin.subjects.index') }}" class="flex items-center text-sm text-white hover:text-amber-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span>Kembali ke Daftar Mata Pelajaran</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <!-- Form errors -->
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-600 text-red-700 p-4 mb-6 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle mt-0.5"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-700">Ada beberapa kesalahan pada inputan Anda:</p>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Add this somewhere at the top of your form for debugging -->
        @if(isset($teachers) && $teachers->isEmpty())
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span>
                        <strong>Perhatian:</strong> Tidak ada guru yang tersedia. 
                        Pastikan ada pengguna dengan peran Guru (role_id=2) dalam database.
                        <a href="{{ url('/diagnostic/teachers') }}" target="_blank" class="text-blue-600 underline">
                            Lihat Diagnostik
                        </a>
                    </span>
                </div>
            </div>
        @endif

        <!-- Create form -->
        <form action="{{ route('admin.subjects.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Subject code -->
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-1">
                    Kode Mata Pelajaran <span class="text-red-600">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-code text-gray-400"></i>
                    </div>
                    <input type="text" name="code" id="code" value="{{ old('code') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 sm:text-sm" placeholder="Contoh: MTK01" required>
                </div>
                <p class="mt-1 text-sm text-gray-500">
                    Kode mata pelajaran harus unik dan menggunakan huruf kapital dan angka.
                </p>
            </div>

            <!-- Subject name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Mata Pelajaran <span class="text-red-600">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-book-open text-gray-400"></i>
                    </div>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 sm:text-sm" placeholder="Nama mata pelajaran" required>
                </div>
            </div>

            <!-- Subject description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                    Deskripsi
                </label>
                <div class="relative">
                    <div class="absolute top-3 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-align-left text-gray-400"></i>
                    </div>
                    <textarea name="description" id="description" rows="4" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 sm:text-sm" placeholder="Deskripsi singkat tentang mata pelajaran ini">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Assign teachers -->
            <div>
                <label for="teachers" class="block text-sm font-medium text-gray-700 mb-1">
                    Guru Pengajar
                </label>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-chalkboard-teacher text-gray-400"></i>
                    </div>
                    <select name="teachers[]" id="teachers" multiple class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 sm:text-sm" size="5">
                        @forelse ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ in_array($teacher->id, old('teachers', [])) ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @empty
                            <option disabled>Tidak ada akun guru yang tersedia</option>
                        @endforelse
                    </select>
                </div>
                <p class="mt-1 text-sm text-gray-500">
                    Tahan tombol Ctrl (Windows) atau Command (Mac) untuk memilih beberapa guru
                </p>
            </div>

            <!-- Add near the teacher selection -->
            @if($teachers->isEmpty())
                <div class="mb-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span>
                            <strong>Perhatian:</strong> Tidak ada guru yang tersedia. 
                            Pastikan ada pengguna dengan peran Guru (role_id=2) dalam database.
                        </span>
                    </div>
                </div>
            @endif

            <!-- Form actions -->
            <div class="flex items-center justify-end space-x-3 pt-5 border-t border-gray-200">
                <a href="{{ route('admin.subjects.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    <i class="fas fa-save mr-2"></i>
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-capitalize subject code as the user types
        const codeInput = document.getElementById('code');
        codeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    });
</script>
@endpush
