@extends('layouts.dashboard')

@section('title', 'Manajemen Mata Pelajaran')

@section('header', 'Manajemen Mata Pelajaran')

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
        <!-- Success/Error messages -->
        @if(session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <div class="flex justify-between mb-4">
            <h2 class="text-xl font-medium">Daftar Mata Pelajaran</h2>
            <a href="{{ route('admin.subjects.create') }}" class="px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition-all">
                <i class="fas fa-plus mr-2"></i>Tambah Mata Pelajaran
            </a>
        </div>

        <!-- Filter and Search Form -->
        <div class="mb-4 p-4 bg-gray-50 rounded-lg">
            <form action="{{ route('admin.subjects.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-grow min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau kode..." 
                        class="w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50">
                </div>
                <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                @if(request()->anyFilled(['search']))
                    <a href="{{ route('admin.subjects.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mata Pelajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru Pengajar</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($subjects ?? [] as $subject)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-md bg-amber-100 text-amber-800 font-medium text-sm">
                                    {{ $subject->code }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $subject->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $subject->description ? \Illuminate\Support\Str::limit($subject->description, 50) : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @if($subject->teachers && $subject->teachers->count() > 0)
                                    <span class="px-2.5 py-0.5 rounded-md bg-green-100 text-green-800 font-medium text-xs">
                                        {{ $subject->teachers->count() }} guru
                                    </span>
                                @else
                                    <span class="text-xs text-gray-500">Belum ada pengajar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2 justify-end">
                                    <a href="{{ route('admin.subjects.show', $subject->id) }}" class="px-3 py-1 bg-amber-500 text-white rounded-md hover:bg-amber-600 transition-colors" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.subjects.edit', $subject->id) }}" class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="delete-button px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors" title="Hapus"
                                        data-subject-id="{{ $subject->id }}" 
                                        data-subject-name="{{ $subject->name }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $subject->id }}" action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="h-16 w-16 bg-amber-100 text-amber-400 rounded-full flex items-center justify-center mb-3">
                                        <i class="fas fa-book text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500 mb-3">Belum ada mata pelajaran yang tersedia</p>
                                    <a href="{{ route('admin.subjects.create') }}" class="px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition-colors">
                                        <i class="fas fa-plus mr-2"></i>Tambah Mata Pelajaran
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if(isset($subjects) && method_exists($subjects, 'links'))
            <div class="mt-4">
                {{ $subjects->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete confirmation with improved UX
        const deleteButtons = document.querySelectorAll('.delete-button');
        
        if (deleteButtons.length > 0) {
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
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
            });
        }
    });
</script>
@endpush
