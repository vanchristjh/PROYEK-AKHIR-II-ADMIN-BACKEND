@extends('layouts.dashboard')

@section('title', 'Kelola Kelas')

@section('header', 'Kelola Kelas')

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
        <a href="{{ route('admin.subjects.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-book text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Mata Pelajaran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.classrooms.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-3 text-white">
            <i class="fas fa-school text-lg w-6"></i>
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
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-school text-9xl"></i>
        </div>
        <div class="relative z-10">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Manajemen Kelas</h2>
                    <p class="text-purple-100">Kelola informasi kelas, wali kelas, dan mata pelajaran.</p>
                </div>
                <div class="hidden md:flex space-x-2">
                    <a href="{{ route('admin.classrooms.create') }}" class="px-4 py-2 bg-white text-purple-600 rounded-lg hover:bg-purple-100 transition-all duration-300 flex items-center shadow-md">
                        <i class="fas fa-plus mr-2"></i> Tambah Kelas
                    </a>
                    <a href="{{ route('admin.classrooms.export-all') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-300 flex items-center shadow-md">
                        <i class="fas fa-file-export mr-2"></i> Export Semua
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <div class="p-2 bg-purple-100 rounded-lg mr-3">
                <i class="fas fa-school text-purple-600"></i>
            </div>
            <h3 class="text-xl font-medium text-gray-800">Daftar Kelas</h3>
        </div>
        <a href="{{ route('admin.classrooms.create') }}" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Kelas Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md shadow-sm animate-fade-in">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Search and filter -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6 border border-gray-100/50">
        <div class="p-4 bg-gray-50">
            <form action="{{ route('admin.classrooms.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-grow min-w-[200px]">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Kelas</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nama atau tingkat kelas..." 
                            class="pl-10 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                    </div>
                </div>
                <div>
                    <label for="grade_level" class="block text-sm font-medium text-gray-700 mb-1">Filter Tingkat</label>
                    <select name="grade_level" id="grade_level" class="rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50" onchange="this.form.submit()">
                        <option value="">Semua Tingkat</option>
                        <option value="10" {{ request('grade_level') == '10' ? 'selected' : '' }}>Kelas 10</option>
                        <option value="11" {{ request('grade_level') == '11' ? 'selected' : '' }}>Kelas 11</option>
                        <option value="12" {{ request('grade_level') == '12' ? 'selected' : '' }}>Kelas 12</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                @if(request()->anyFilled(['search', 'grade_level']))
                    <a href="{{ route('admin.classrooms.index') }}" class="px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        <i class="fas fa-times mr-2"></i> Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        @if($classrooms->isEmpty())
            <div class="p-8 text-center">
                <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-purple-100 text-purple-600 mb-4">
                    <i class="fas fa-school text-2xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-800 mb-1">Tidak ada kelas</h4>
                <p class="text-gray-500 mb-4">Belum ada data kelas yang tersedia</p>
                <a href="{{ route('admin.classrooms.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambah Kelas Baru
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kelas</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat/Tahun Akademik</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wali Kelas</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan/Kapasitas</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($classrooms as $classroom)
                            <tr class="hover:bg-gray-50 transition-colors animate-item" style="animation-delay: {{ $loop->index * 50 }}ms">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600">
                                            <i class="fas fa-chalkboard-teacher"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $classroom->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Kelas {{ $classroom->grade_level }}
                                        </span>
                                        <span class="ml-2 text-sm text-gray-500">{{ $classroom->academic_year }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $classroom->homeroomTeacher->name ?? 'Belum ditetapkan' }}</div>
                                    @if($classroom->homeroomTeacher)
                                        <div class="text-xs text-gray-500">NIP: {{ $classroom->homeroomTeacher->id_number ?? '-' }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($classroom->room_number)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-door-open mr-1 text-gray-500"></i>
                                                {{ $classroom->room_number }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500">
                                                <i class="fas fa-question-circle mr-1"></i>
                                                Belum ditentukan
                                            </span>
                                        @endif
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-users mr-1"></i>
                                            {{ $classroom->capacity }} siswa
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.classrooms.show', $classroom) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 hover:bg-indigo-200 p-1.5 rounded-md transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.classrooms.edit', $classroom) }}" class="text-purple-600 hover:text-purple-900 bg-purple-100 hover:bg-purple-200 p-1.5 rounded-md transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="confirmDelete('{{ $classroom->name }}', '{{ route('admin.classrooms.destroy', $classroom) }}')" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 p-1.5 rounded-md transition-colors">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($classrooms->hasPages())
                <div class="mt-4">
                    {{ $classrooms->appends(request()->query())->links('pagination::tailwind') }}
                </div>
            @endif
        @endif
    </div>
    
    <!-- Delete Modal -->
    <div class="fixed inset-0 z-50 flex items-center justify-center hidden" id="deleteModal">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" id="modalOverlay"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full mx-auto shadow-xl z-50 overflow-hidden transform transition-all">
            <div class="bg-white px-6 py-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Hapus Kelas</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="modal-description">
                                Apakah Anda yakin ingin menghapus kelas ini? Tindakan ini tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Hapus
                    </button>
                    <button type="button" id="cancelDelete" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .animate-fade-in {
        animation: fade-in 0.6s ease-in-out;
    }
    
    .animate-item {
        animation: item-appear 0.5s ease-out forwards;
        opacity: 0;
    }
    
    @keyframes item-appear {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
</style>
@endpush

@push('scripts')
<script>
    function confirmDelete(className, deleteUrl) {
        const modal = document.getElementById('deleteModal');
        const modalDescription = document.getElementById('modal-description');
        const deleteForm = document.getElementById('deleteForm');
        
        modalDescription.textContent = `Apakah Anda yakin ingin menghapus kelas "${className}"? Tindakan ini tidak dapat dibatalkan.`;
        deleteForm.action = deleteUrl;
        modal.classList.remove('hidden');
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('deleteModal');
        const modalOverlay = document.getElementById('modalOverlay');
        const cancelDelete = document.getElementById('cancelDelete');
        
        modalOverlay.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
        
        cancelDelete.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
    });
</script>
@endpush
