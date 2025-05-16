@extends('layouts.dashboard')

@section('title', 'Detail Mata Pelajaran')

@section('header', 'Detail Mata Pelajaran')

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
    <!-- Breadcrumb -->
    <nav class="mb-4 text-sm" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-amber-600">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                    <a href="{{ route('admin.subjects.index') }}" class="text-gray-700 hover:text-amber-600">
                        Mata Pelajaran
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                    <span class="text-gray-500" aria-current="page">
                        {{ $subject->name }}
                    </span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Success/Error messages -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Subject Header -->
    <div class="bg-gradient-to-r from-amber-500 to-amber-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-book text-9xl"></i>
        </div>
        <div class="relative flex justify-between">
            <div>
                <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-800 text-amber-100 mb-2">
                    {{ $subject->code }}
                </div>
                <h2 class="text-2xl font-bold mb-2">{{ $subject->name }}</h2>
                <p class="text-amber-100 mb-4">
                    {{ $subject->description ?? 'Tidak ada deskripsi' }}
                </p>
                <div class="flex items-center text-amber-100">
                    <i class="fas fa-chalkboard-teacher mr-2"></i>
                    <span>{{ $subject->teachers->count() }} Guru</span>
                    <i class="fas fa-school ml-4 mr-2"></i>
                    <span>{{ $subject->classrooms->count() }} Kelas</span>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.subjects.edit', $subject->id) }}" class="inline-flex items-center px-4 py-2 bg-white/10 rounded-lg hover:bg-white/20 transition-colors backdrop-blur-sm">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
                <a href="{{ route('admin.subjects.download', $subject->id) }}" class="inline-flex items-center px-4 py-2 bg-white/10 rounded-lg hover:bg-white/20 transition-colors backdrop-blur-sm">
                    <i class="fas fa-download mr-2"></i>
                    Unduh Materi
                </a>
                <button type="button" class="delete-button inline-flex items-center px-4 py-2 bg-red-500/20 text-white rounded-lg hover:bg-red-500/40 transition-colors backdrop-blur-sm"
                    data-subject-id="{{ $subject->id }}" 
                    data-subject-name="{{ $subject->name }}">
                    <i class="fas fa-trash mr-2"></i>
                    Hapus
                </button>
                <form id="delete-form-{{ $subject->id }}" action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Teachers assigned -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-blue-50 px-4 py-3 border-b border-blue-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-blue-800">
                        <i class="fas fa-chalkboard-teacher mr-2 text-blue-600"></i>
                        Guru Pengajar
                    </h3>
                    <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-medium">
                        {{ $subject->teachers->count() }}
                    </span>
                </div>
            </div>
            <div class="p-4">
                @if($subject->teachers->count() > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($subject->teachers as $teacher)
                            <li class="py-3 flex items-center">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 mr-3">
                                    @if($teacher->avatar)
                                        <img src="{{ asset('storage/' . $teacher->avatar) }}" alt="{{ $teacher->name }}" class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <span class="font-medium text-sm">{{ substr($teacher->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $teacher->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $teacher->email }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="py-4 text-center text-gray-500 italic">
                        Belum ada guru yang ditugaskan
                    </div>
                @endif
            </div>
        </div>

        <!-- Classrooms -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-green-50 px-4 py-3 border-b border-green-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-green-800">
                        <i class="fas fa-school mr-2 text-green-600"></i>
                        Kelas yang Diajar
                    </h3>
                    <span class="px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs font-medium">
                        {{ $subject->classrooms->count() }}
                    </span>
                </div>
            </div>
            <div class="p-4">
                @if($subject->classrooms->count() > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($subject->classrooms as $classroom)
                            <li class="py-3 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center text-green-700 mr-3">
                                        <i class="fas fa-school"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $classroom->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $classroom->grade_level }}</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full bg-green-100 text-green-800 text-xs font-medium">
                                    {{ $classroomStudentCounts[$classroom->id] ?? 0 }} Siswa
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="py-4 text-center text-gray-500 italic">
                        Belum ada kelas yang menggunakan mata pelajaran ini
                    </div>
                @endif
            </div>
        </div>

        <!-- Materials and Assignments -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-purple-50 px-4 py-3 border-b border-purple-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-purple-800">
                        <i class="fas fa-file-alt mr-2 text-purple-600"></i>
                        Konten Pembelajaran
                    </h3>
                </div>
            </div>
            <div class="p-4">
                <div class="flex items-center justify-between mb-3 pb-3 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 mr-2">
                            <i class="fas fa-book-reader"></i>
                        </div>
                        <span class="font-medium text-gray-800">Materi</span>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-purple-100 text-purple-800 text-xs font-medium">
                        {{ $subject->materials->count() }}
                    </span>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="h-8 w-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-700 mr-2">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <span class="font-medium text-gray-800">Tugas</span>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-medium">
                        {{ $subject->assignments->count() }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Back button -->
    <div class="mt-6">
        <a href="{{ route('admin.subjects.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Mata Pelajaran
        </a>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete confirmation with improved UX
        const deleteButton = document.querySelector('.delete-button');
        
        if (deleteButton) {
            deleteButton.addEventListener('click', function() {
                const subjectId = this.dataset.subjectId;
                const subjectName = this.dataset.subjectName;
                
                // Create modal overlay
                const overlay = document.createElement('div');
                overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center';
                
                // Create confirmation modal
                const modal = document.createElement('div');
                modal.className = 'bg-white rounded-lg shadow-xl p-6 max-w-md mx-4 animate-fade-in';
                modal.innerHTML = `
                    <div class="text-center mb-4">
                        <div class="h-16 w-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Konfirmasi Penghapusan</h3>
                        <p class="text-gray-600">Apakah Anda yakin ingin menghapus mata pelajaran <span class="font-medium">"${subjectName}"</span>?</p>
                        <p class="text-sm text-red-600 mt-2">Tindakan ini akan menghapus semua data terkait dan tidak dapat dibatalkan.</p>
                    </div>
                    <div class="flex justify-center space-x-3">
                        <button type="button" class="cancel-button px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                            Batal
                        </button>
                        <button type="button" class="confirm-button px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Ya, Hapus
                        </button>
                    </div>
                `;
                
                overlay.appendChild(modal);
                document.body.appendChild(overlay);
                
                // Handle cancel button
                modal.querySelector('.cancel-button').addEventListener('click', function() {
                    overlay.classList.add('opacity-0');
                    setTimeout(() => overlay.remove(), 300);
                });
                
                // Handle confirm button
                modal.querySelector('.confirm-button').addEventListener('click', function() {
                    // Show loading state
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghapus...';
                    this.disabled = true;
                    document.getElementById(`delete-form-${subjectId}`).submit();
                });
                
                // Close on outside click
                overlay.addEventListener('click', function(e) {
                    if (e.target === overlay) {
                        overlay.classList.add('opacity-0');
                        setTimeout(() => overlay.remove(), 300);
                    }
                });
            });
        }
    });
</script>
@endpush