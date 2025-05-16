@extends('layouts.dashboard')

@php
use Illuminate\Support\Str;
@endphp

@section('title', 'Materi Pelajaran')

@section('header', 'Materi Pelajaran')

@section('navigation')
    <li>
        <a href="{{ route('guru.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-indigo-700 transition-all duration-200">
                <i class="fas fa-tachometer-alt text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.materials.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-2.5 group relative text-white">
            <div class="p-1.5 rounded-lg bg-green-800 transition-all duration-200">
                <i class="fas fa-book text-lg w-5 h-5 flex items-center justify-center text-white"></i>
            </div>
            <span class="ml-3">Materi Pelajaran</span>
            <span class="absolute inset-y-0 left-0 w-1 bg-green-400 rounded-tr-md rounded-br-md"></span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.assignments.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-green-700/50 transition-all duration-200">
                <i class="fas fa-tasks text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Tugas</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.attendance.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-purple-700/50 transition-all duration-200">
                <i class="fas fa-clipboard-check text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Kehadiran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.announcements.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-red-700/50 transition-all duration-200">
                <i class="fas fa-bullhorn text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="bg-gradient-to-r from-green-500 to-teal-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-book text-9xl"></i>
        </div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute right-1/3 -top-12 w-36 h-36 bg-green-300/20 rounded-full blur-3xl"></div>
        <div class="relative z-10">
            <div class="flex items-center">
                <div class="bg-white/20 p-2 rounded-lg shadow-inner backdrop-blur-sm mr-4">
                    <i class="fas fa-book text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold mb-1">Materi Pelajaran</h2>
                    <p class="text-green-100">Kelola semua materi pembelajaran untuk siswa</p>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg mr-3">
                <i class="fas fa-book text-green-600"></i>
            </div>
            <h3 class="text-xl font-medium text-gray-800">Daftar Materi</h3>
        </div>
        <a href="{{ route('guru.materials.create') }}" class="px-4 py-2 bg-gradient-to-r from-green-600 to-teal-700 text-white rounded-lg hover:from-green-700 hover:to-teal-800 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Materi
        </a>
    </div>
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    <!-- Filter section -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <form action="{{ route('guru.materials.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-grow min-w-[200px]">
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                <select name="subject" id="subject" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach(auth()->user()->teacherSubjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-shrink-0">
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
            </div>
            @if(request('subject'))
                <div class="flex-shrink-0">
                    <a href="{{ route('guru.materials.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors inline-block">
                        <i class="fas fa-times mr-2"></i> Reset
                    </a>
                </div>
            @endif
        </form>
    </div>
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($materials->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                @foreach($materials as $material)
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                        <div class="p-5">
                            <div class="flex items-start justify-between">
                                <div class="h-10 w-10 rounded-lg flex items-center justify-center 
                                    {{ $material->attachment_path ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500' }}">
                                    <i class="fas {{ $material->attachment_path ? 'fa-file-alt' : 'fa-align-left' }} text-xl"></i>
                                </div>
                                <div class="dropdown relative">
                                    <button class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-10">
                                        <a href="{{ route('guru.materials.show', $material) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-eye mr-2"></i> Lihat Detail
                                        </a>
                                        <a href="{{ route('guru.materials.edit', $material) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-edit mr-2"></i> Edit
                                        </a>
                                        <form action="{{ route('guru.materials.destroy', $material) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                <i class="fas fa-trash-alt mr-2"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-gray-800">{{ $material->title }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ Str::limit($material->description, 80) }}</p>
                            <div class="mt-4 flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-book mr-1"></i> {{ $material->subject->name }}
                                </span>
                                <span class="ml-2 text-xs text-gray-500">{{ $material->created_at->format('d M Y') }}</span>
                            </div>
                            <a href="{{ route('guru.materials.show', $material) }}" class="mt-4 inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                                Lihat Lebih Detail
                                <i class="fas fa-arrow-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center">
                <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                    <i class="fas fa-book text-2xl"></i>
                </div>
                <h3 class="text-base font-medium text-gray-800 mb-1">Belum ada materi pelajaran</h3>
                <p class="text-gray-500">Buat materi pelajaran baru dengan mengklik tombol "Tambah Materi" di atas.</p>
                <div class="mt-6">
                    <a href="{{ route('guru.materials.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Tambah Materi Sekarang
                    </a>
                </div>
            </div>
        @endif
        <div class="px-6 py-4 border-t">
            {{ $materials->links() }}
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Toggle dropdown menu
    document.addEventListener('DOMContentLoaded', function() {
        const dropdowns = document.querySelectorAll('.dropdown');
        
        dropdowns.forEach(dropdown => {
            const button = dropdown.querySelector('button');
            const menu = dropdown.querySelector('.dropdown-menu');
            
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                menu.classList.toggle('hidden');
            });
        });

        document.addEventListener('click', function() {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        });
    });
</script>
@endpush
