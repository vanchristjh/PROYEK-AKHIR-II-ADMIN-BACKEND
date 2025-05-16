@extends('layouts.dashboard')

@section('title', 'Detail Kelas')

@section('header', 'Detail Kelas')

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
                    <h2 class="text-2xl font-bold mb-2">{{ $classroom->name }}</h2>
                    <p class="text-purple-100">Detail dan informasi kelas</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.classrooms.edit', $classroom) }}" class="px-4 py-2 bg-white text-purple-600 rounded-lg hover:bg-purple-100 transition-all duration-300 flex items-center shadow-md">
                        <i class="fas fa-edit mr-2"></i> Edit Kelas
                    </a>
                    <a href="{{ route('admin.classrooms.export', $classroom) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-300 flex items-center shadow-md">
                        <i class="fas fa-file-export mr-2"></i> Export PDF
                    </a>
                    <a href="{{ route('admin.classrooms.index') }}" class="px-4 py-2 bg-purple-700 text-white rounded-lg hover:bg-purple-800 transition-all duration-300 flex items-center shadow-md">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition-all duration-300 flex items-center relative overflow-hidden">
            <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 mr-4">
                <i class="fas fa-user-graduate text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Jumlah Siswa</p>
                <div class="flex items-center">
                    @php
                        $studentCount = $classroom->students->count();
                    @endphp
                    <span class="text-2xl font-bold text-gray-800">{{ $studentCount }}</span>
                    <span class="text-sm text-gray-400 ml-2">/ {{ $classroom->capacity }}</span>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 h-20 w-20 opacity-5">
                <i class="fas fa-user-graduate text-7xl"></i>
            </div>
        </div>
        
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition-all duration-300 flex items-center relative overflow-hidden">
            <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600 mr-4">
                <i class="fas fa-book text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Mata Pelajaran</p>
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-gray-800">{{ $classroom->subjects->count() }}</span>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 h-20 w-20 opacity-5">
                <i class="fas fa-book text-7xl"></i>
            </div>
        </div>
        
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition-all duration-300 flex items-center relative overflow-hidden">
            <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center text-green-600 mr-4">
                <i class="fas fa-clipboard-check text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Status Kelas</p>
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-gray-800">{{ $studentCount >= $classroom->capacity ? 'Penuh' : 'Tersedia' }}</span>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 h-20 w-20 opacity-5">
                <i class="fas fa-clipboard-check text-7xl"></i>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 transform transition hover:shadow-md mb-6">
                <div class="p-5 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-purple-100">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-200 rounded-lg mr-3 shadow-inner">
                            <i class="fas fa-info-circle text-purple-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Informasi Kelas</h3>
                    </div>
                </div>
                
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Nama Kelas</h4>
                                <p class="text-lg font-semibold text-gray-800">{{ $classroom->name }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Tingkat</h4>
                                <p class="text-lg font-semibold text-gray-800">Kelas {{ $classroom->grade_level }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Tahun Akademik</h4>
                                <p class="text-lg font-semibold text-gray-800">{{ $classroom->academic_year }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Wali Kelas</h4>
                                @if($classroom->homeroomTeacher)
                                    <div class="flex items-center mt-1">
                                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 mr-2">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <p class="text-md font-semibold text-gray-800">{{ $classroom->homeroomTeacher->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $classroom->homeroomTeacher->email }}</p>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-red-500">Belum ditetapkan</p>
                                @endif
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Ruangan</h4>
                                <p class="text-lg font-semibold text-gray-800">{{ $classroom->room_number ?? 'Belum ditetapkan' }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Kapasitas</h4>
                                <p class="text-lg font-semibold text-gray-800">{{ $classroom->capacity }} siswa</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subjects Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 transform transition hover:shadow-md mb-6">
                <div class="p-5 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-amber-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-2 bg-amber-200 rounded-lg mr-3 shadow-inner">
                                <i class="fas fa-book text-amber-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Mata Pelajaran</h3>
                        </div>
                        <span class="bg-amber-100 text-amber-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $classroom->subjects->count() }} mata pelajaran
                        </span>
                    </div>
                </div>
                
                <div class="p-5">
                    @if($classroom->subjects->isEmpty())
                        <div class="text-center py-6">
                            <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-amber-100 text-amber-500 mb-4">
                                <i class="fas fa-book-open text-2xl"></i>
                            </div>
                            <h4 class="text-base font-medium text-gray-800 mb-2">Belum Ada Mata Pelajaran</h4>
                            <p class="text-gray-500 mb-4">Kelas ini belum memiliki mata pelajaran yang ditambahkan</p>
                            <a href="{{ route('admin.classrooms.edit', $classroom) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i> Tambahkan Mata Pelajaran
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($classroom->subjects as $subject)
                                <div class="bg-white border border-amber-200 rounded-lg p-3 hover:shadow-md transition-all hover:bg-amber-50/30 hover:-translate-y-0.5 transform duration-300">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            <div class="bg-gradient-to-br from-amber-400 to-amber-600 p-2 rounded-lg mr-3 text-white shadow-sm">
                                                <i class="fas fa-book"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-800">{{ $subject->name }}</h4>
                                                <p class="text-xs text-gray-500">Kode: {{ $subject->code }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.subjects.show', $subject) }}" class="text-amber-600 hover:text-amber-900 bg-amber-100 hover:bg-amber-200 p-1 px-2 rounded-md transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Students Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 transform transition hover:shadow-md">
                <div class="p-5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-200 rounded-lg mr-3 shadow-inner">
                                <i class="fas fa-user-graduate text-blue-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Siswa</h3>
                        </div>
                        <div class="flex space-x-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $studentCount }}/{{ $classroom->capacity }} siswa
                            </span>
                            <a href="{{ route('admin.users.create') }}?role=siswa&classroom_id={{ $classroom->id }}" 
                               class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full hover:bg-blue-200">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="p-5 max-h-[500px] overflow-y-auto">
                    @php
                        $students = $classroom->students;
                    @endphp
                    
                    @if($students->isEmpty())
                        <div class="text-center py-6">
                            <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 text-blue-500 mb-4">
                                <i class="fas fa-user-graduate text-2xl"></i>
                            </div>
                            <h4 class="text-base font-medium text-gray-800 mb-2">Belum Ada Siswa</h4>
                            <p class="text-gray-500 mb-4">Kelas ini belum memiliki siswa yang terdaftar</p>
                            <a href="{{ route('admin.users.create') }}?role=siswa&classroom_id={{ $classroom->id }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                <i class="fas fa-user-plus mr-2"></i> Tambahkan Siswa
                            </a>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($students as $student)
                                <div class="flex items-center justify-between p-2 border border-gray-200 rounded-lg hover:bg-blue-50 transition-all duration-300">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white shadow-sm">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $student->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $student->email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-1">
                                        <a href="{{ route('admin.users.show', $student) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50/50 hover:bg-blue-100 p-1 px-2 rounded-md transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $student) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50/50 hover:bg-blue-100 p-1 px-2 rounded-md transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" onclick="confirmRemoveStudent('{{ $student->name }}', '{{ route('admin.classrooms.removeStudent', ['classroom' => $classroom->id, 'student' => $student->id]) }}')" 
                                                class="text-red-600 hover:text-red-900 bg-red-50/50 hover:bg-red-100 p-1 px-2 rounded-md transition-colors">
                                            <i class="fas fa-user-minus"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('admin.users.create') }}?role=siswa&classroom_id={{ $classroom->id }}" class="inline-flex items-center px-3 py-1 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                <i class="fas fa-user-plus mr-2"></i> Tambah Siswa
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-6 flex flex-wrap space-x-2">
        <a href="{{ route('admin.classrooms.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors mb-2">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Kelas
        </a>
        <a href="{{ route('admin.classrooms.edit', $classroom) }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors mb-2">
            <i class="fas fa-edit mr-2"></i> Edit Kelas
        </a>
        <button onclick="confirmDelete('{{ $classroom->name }}', '{{ route('admin.classrooms.destroy', $classroom) }}')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors mb-2">
            <i class="fas fa-trash mr-2"></i> Hapus Kelas
        </button>
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
    
    <!-- Remove Student Modal -->
    <div class="fixed inset-0 z-50 flex items-center justify-center hidden" id="removeStudentModal">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" id="studentModalOverlay"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full mx-auto shadow-xl z-50 overflow-hidden transform transition-all">
            <div class="bg-white px-6 py-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-user-minus text-yellow-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Keluarkan Siswa</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="student-modal-description">
                                Apakah Anda yakin ingin mengeluarkan siswa ini dari kelas?
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <form id="removeStudentForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Keluarkan
                    </button>
                    <button type="button" id="cancelRemoveStudent" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
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
    
    function confirmRemoveStudent(studentName, removeUrl) {
        const modal = document.getElementById('removeStudentModal');
        const modalDescription = document.getElementById('student-modal-description');
        const removeForm = document.getElementById('removeStudentForm');
        
        modalDescription.textContent = `Apakah Anda yakin ingin mengeluarkan siswa "${studentName}" dari kelas ini?`;
        removeForm.action = removeUrl;
        modal.classList.remove('hidden');
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Delete classroom modal handlers
        const modal = document.getElementById('deleteModal');
        const modalOverlay = document.getElementById('modalOverlay');
        const cancelDelete = document.getElementById('cancelDelete');
        
        modalOverlay.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
        
        cancelDelete.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
        
        // Remove student modal handlers
        const studentModal = document.getElementById('removeStudentModal');
        const studentModalOverlay = document.getElementById('studentModalOverlay');
        const cancelRemoveStudent = document.getElementById('cancelRemoveStudent');
        
        studentModalOverlay.addEventListener('click', function() {
            studentModal.classList.add('hidden');
        });
        
        cancelRemoveStudent.addEventListener('click', function() {
            studentModal.classList.add('hidden');
        });
        
        // Display flash messages if they exist
        if (document.querySelector('.flash-message')) {
            setTimeout(function() {
                const flashMessages = document.querySelectorAll('.flash-message');
                flashMessages.forEach(message => {
                    message.classList.add('opacity-0');
                    setTimeout(() => {
                        message.remove();
                    }, 300);
                });
            }, 3000);
        }
    });
</script>
@endpush