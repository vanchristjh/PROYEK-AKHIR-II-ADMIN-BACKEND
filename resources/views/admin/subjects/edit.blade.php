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
    <li>
        <a href="{{ route('admin.announcements.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-bullhorn text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="mb-4 pb-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-medium">Edit Mata Pelajaran</h2>
                <a href="{{ route('admin.subjects.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-600 text-green-700 p-4 mb-6 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle mt-0.5"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-600 text-red-700 p-4 mb-6 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle mt-0.5"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">Ada beberapa kesalahan pada inputan Anda:</p>
                        <ul class="mt-2 text-sm list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Edit form -->
        <form action="{{ route('admin.subjects.update', $subject->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Subject code -->
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-1">
                    Kode Mata Pelajaran <span class="text-red-600">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-tag text-gray-400"></i>
                    </div>
                    <input type="text" name="code" id="code" value="{{ old('code', $subject->code) }}" required
                        class="pl-10 w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50 @error('code') border-red-500 @enderror">
                </div>
                @error('code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @else
                    <p class="mt-1 text-sm text-gray-500">Kode unik untuk mata pelajaran ini (contoh: MTK, IPA, BIG)</p>
                @enderror
            </div>

            <!-- Subject name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Mata Pelajaran <span class="text-red-600">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-book text-gray-400"></i>
                    </div>
                    <input type="text" name="name" id="name" value="{{ old('name', $subject->name) }}" required
                        class="pl-10 w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50 @error('name') border-red-500 @enderror">
                </div>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subject description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                    Deskripsi Mata Pelajaran
                </label>
                <div class="relative">
                    <textarea name="description" id="description" rows="4"
                        class="w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50 @error('description') border-red-500 @enderror">{{ old('description', $subject->description) }}</textarea>
                </div>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @else
                    <p class="mt-1 text-sm text-gray-500">Deskripsi singkat tentang mata pelajaran ini (opsional)</p>
                @enderror
            </div>

            <!-- Teacher selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Guru Pengajar
                </label>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 @error('teachers') border-red-500 @enderror">
                    <div class="mb-2 pb-2 border-b border-gray-200">
                        <input type="text" id="teacherSearch" placeholder="Cari guru..." class="w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                    
                    <div class="max-h-60 overflow-y-auto" id="teachersList">
                        @forelse ($teachers as $teacher)
                            <div class="flex items-center py-2 teacher-item">
                                <input type="checkbox" name="teachers[]" id="teacher-{{ $teacher->id }}" value="{{ $teacher->id }}"
                                    {{ in_array($teacher->id, old('teachers', $subject->teachers->pluck('id')->toArray())) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                <label for="teacher-{{ $teacher->id }}" class="ml-2 text-sm text-gray-700 flex items-center">
                                    <span class="font-medium">{{ $teacher->name }}</span>
                                    @if($teacher->email)
                                        <span class="ml-2 text-xs text-gray-500">({{ $teacher->email }})</span>
                                    @endif
                                </label>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 py-2">Tidak ada guru yang tersedia</p>
                        @endforelse
                    </div>
                </div>
                @error('teachers')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @else
                    <p class="mt-1 text-sm text-gray-500">Pilih guru yang akan mengajar mata pelajaran ini</p>
                @enderror
            </div>

            <div class="flex justify-end pt-5 border-t border-gray-200">
                <a href="{{ route('admin.subjects.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 mr-2 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition-all">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('teacherSearch');
            const teacherItems = document.querySelectorAll('.teacher-item');
            
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                
                teacherItems.forEach(item => {
                    const teacherName = item.querySelector('label').textContent.toLowerCase();
                    if (teacherName.includes(searchTerm)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>
    @endpush
@endsection
