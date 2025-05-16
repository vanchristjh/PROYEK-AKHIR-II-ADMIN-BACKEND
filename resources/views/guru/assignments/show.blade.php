@extends('layouts.dashboard')

@section('title', 'Detail Tugas')

@section('header', 'Detail Tugas')

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
        <a href="{{ route('guru.materials.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-green-700/50 transition-all duration-200">
                <i class="fas fa-book text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Materi Pelajaran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.assignments.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-2.5 group relative text-white">
            <div class="p-1.5 rounded-lg bg-green-800 transition-all duration-200">
                <i class="fas fa-tasks text-lg w-5 h-5 flex items-center justify-center text-white"></i>
            </div>
            <span class="ml-3">Tugas</span>
            <span class="absolute inset-y-0 left-0 w-1 bg-green-400 rounded-tr-md rounded-br-md"></span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.grades.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-purple-700/50 transition-all duration-200">
                <i class="fas fa-star text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Penilaian</span>
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
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-500 to-blue-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-tasks text-9xl"></i>
        </div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute right-1/3 -top-12 w-36 h-36 bg-blue-300/20 rounded-full blur-3xl"></div>
        <div class="relative z-10">
            <div class="flex items-center">
                <div class="bg-white/20 p-2 rounded-lg shadow-inner backdrop-blur-sm mr-4">
                    <i class="fas fa-clipboard-list text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold mb-1">{{ $assignment->title }}</h2>
                    <p class="text-indigo-100">Detail dan pengumpulan tugas siswa</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Assignment Details -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-medium text-gray-800">Informasi Tugas</h3>
                    <div class="flex space-x-2">
                        <a href="{{ route('guru.assignments.edit', $assignment) }}" class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors flex items-center">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                        <button onclick="confirmDelete('{{ $assignment->title }}', '{{ route('guru.assignments.destroy', $assignment) }}')" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors flex items-center">
                            <i class="fas fa-trash-alt mr-2"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <div class="text-sm text-gray-500">Kelas</div>
                            <div class="font-medium">{{ $assignment->classroom->name }}</div>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <div class="text-sm text-gray-500">Mata Pelajaran</div>
                            <div class="font-medium">{{ $assignment->subject->name }}</div>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <div class="text-sm text-gray-500">Tanggal Dibuat</div>
                            <div class="font-medium">{{ $assignment->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <div class="text-sm text-gray-500">Deadline</div>
                            <div class="font-medium">
                                @if($assignment->deadline)
                                    {{ $assignment->deadline->format('d M Y, H:i') }}
                                    @php
                                        $now = \Carbon\Carbon::now();
                                        $isActive = $assignment->deadline > $now;
                                    @endphp
                                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $isActive ? 'Aktif' : 'Kedaluwarsa' }}
                                    </span>
                                @else
                                    Tidak Ada
                                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800">Tanpa Batas Waktu</span>
                                @endif
                            </div>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg sm:col-span-2">
                            <div class="text-sm text-gray-500">Nilai Maksimal</div>
                            <div class="font-medium">{{ $assignment->max_score }}</div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-medium text-gray-800 mb-2">Deskripsi Tugas</h4>
                        <div class="prose max-w-none bg-gray-50 p-4 rounded-lg">
                            {!! nl2br(e($assignment->description)) !!}
                        </div>
                    </div>

                    @if($assignment->file_path)
                    <div>
                        <h4 class="font-medium text-gray-800 mb-2">File Tugas</h4>
                        <a href="{{ Storage::url($assignment->file_path) }}" target="_blank" 
                            class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
                            <div class="bg-blue-100 text-blue-700 p-2 rounded-lg mr-3 group-hover:bg-blue-200">
                                <i class="fas fa-file-download"></i>
                            </div>
                            <div>
                                <div class="font-medium text-blue-700">{{ $assignment->file_name }}</div>
                                <div class="text-xs text-gray-500">Klik untuk mengunduh</div>
                            </div>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Submission Stats -->
        <div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
                <h3 class="text-xl font-medium text-gray-800 mb-4">Status Pengumpulan</h3>
                
                <div class="space-y-4">
                    <!-- Donut Chart -->
                    <div class="flex justify-center mb-3">
                        <div class="relative h-36 w-36">
                            <canvas id="submissionChart"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-2xl font-bold">{{ $assignment->submissions->count() }}</span>
                                <span class="text-xs text-gray-500">Submissions</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <div class="bg-green-50 p-3 rounded-lg">
                            <div class="text-green-600 text-xl font-bold">{{ $assignment->submissions->count() }}</div>
                            <div class="text-xs text-gray-500">Mengumpulkan</div>
                        </div>
                        <div class="bg-red-50 p-3 rounded-lg">
                            <div class="text-red-600 text-xl font-bold">
                                {{ $assignment->classroom->students->count() - $assignment->submissions->count() }}
                            </div>
                            <div class="text-xs text-gray-500">Belum Mengumpulkan</div>
                        </div>
                    
                        <div class="bg-indigo-50 p-4 rounded-lg text-center">
                            <div class="text-sm text-gray-500 mb-1">Persentase Pengumpulan</div>
                            <div class="text-xl font-bold text-indigo-700">
                                @php
                                    $percentage = $assignment->classroom->students->count() > 0 
                                        ? round(($assignment->submissions->count() / $assignment->classroom->students->count()) * 100) 
                                        : 0;
                                @endphp
                                {{ $percentage }}%
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Student Submissions -->
        <div class="md:col-span-3">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                <div class="flex justify-between items-center p-6 border-b">
                    <h3 class="text-xl font-medium text-gray-800">Pengumpulan Siswa</h3>
                    
                    <div class="relative">
                        <input type="text" id="search-students" placeholder="Cari siswa..." class="pl-10 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="submission-table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengumpulan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($studentSubmissions as $item)
                                <tr class="submission-row hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 student-name">{{ $item['student']->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $item['student']->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item['status'] === 'submitted')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Sudah Mengumpulkan
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Belum Mengumpulkan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item['submitted_at'] ? $item['submitted_at']->format('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item['submission'] && $item['submission']->score !== null)
                                            <span class="text-sm font-medium px-2 py-1 bg-indigo-100 text-indigo-800 rounded">
                                                {{ $item['submission']->score }}/{{ $assignment->max_score }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500">Belum dinilai</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($item['status'] === 'submitted')
                                            <div class="flex space-x-2">
                                                <a href="{{ Storage::url($item['submission']->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 p-1.5 rounded-md transition-colors" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" onclick="openGradeModal('{{ $item['student']->name }}', {{ $item['submission']->id }}, {{ $item['submission']->score }})" class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 hover:bg-indigo-200 p-1.5 rounded-md transition-colors" title="Beri Nilai">
                                                    <i class="fas fa-star"></i>
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty state if no students -->
                @if($studentSubmissions->isEmpty())
                <div class="p-6 text-center">
                    <div class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 text-gray-500 mb-3">
                        <i class="fas fa-users-slash"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-800 mb-1">Tidak ada siswa</h4>
                    <p class="text-gray-500">Tidak ada siswa di kelas ini</p>
                </div>
                @endif
            </div>
        </div>
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
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Hapus Tugas</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="modal-description">
                                Apakah Anda yakin ingin menghapus tugas ini? Semua data terkait termasuk pengumpulan tugas siswa akan terhapus.
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
    
    <!-- Grade Modal -->
    <div class="fixed inset-0 z-50 flex items-center justify-center hidden" id="gradeModal">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" id="gradeModalOverlay"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full mx-auto shadow-xl z-50 overflow-hidden transform transition-all">
            <div class="bg-white px-6 py-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Beri Nilai Tugas</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500" id="closeGradeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-4" id="student-name">
                        Loading...
                    </p>
                    
                    <form id="gradeForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="score" class="block text-sm font-medium text-gray-700 mb-1">Nilai (dari {{ $assignment->max_score }})</label>
                            <input type="number" name="score" id="score" min="0" max="{{ $assignment->max_score }}"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div class="mb-4">
                            <label for="feedback" class="block text-sm font-medium text-gray-700 mb-1">Feedback (Opsional)</label>
                            <textarea name="feedback" id="feedback" rows="3" 
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                        </div>
                        <div class="flex justify-end space-x-2 pt-4 border-t">
                            <button type="button" id="cancelGrade" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                Simpan Nilai
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delete modal handling
        const deleteModal = document.getElementById('deleteModal');
        const modalOverlay = document.getElementById('modalOverlay');
        const cancelDelete = document.getElementById('cancelDelete');
        const deleteForm = document.getElementById('deleteForm');
        
        // Grade modal handling
        const gradeModal = document.getElementById('gradeModal');
        const gradeModalOverlay = document.getElementById('gradeModalOverlay');
        const closeGradeModal = document.getElementById('closeGradeModal');
        const cancelGrade = document.getElementById('cancelGrade');
        const gradeForm = document.getElementById('gradeForm');
        
        // Function to show delete confirmation modal
        window.confirmDelete = function(title, url) {
            // Update the form action URL
            deleteForm.action = url;
            
            // Update modal content with the assignment title
            document.getElementById('modal-description').textContent = 
                `Apakah Anda yakin ingin menghapus tugas "${title}"? Semua data terkait termasuk pengumpulan tugas siswa akan terhapus.`;
            
            // Show the modal
            deleteModal.classList.remove('hidden');
            
            // Prevent body scrolling
            document.body.style.overflow = 'hidden';
        }
        
        // Function to open grade modal
        window.openGradeModal = function(studentName, submissionId, currentScore) {
            // Update student name
            document.getElementById('student-name').textContent = `Siswa: ${studentName}`;
            
            // Update score if available
            if (currentScore !== null) {
                document.getElementById('score').value = currentScore;
            } else {
                document.getElementById('score').value = '';
            }
            
            // Set form action URL
            gradeForm.action = `{{ route('guru.submissions.update', '') }}/${submissionId}`;
            
            // Show the modal
            gradeModal.classList.remove('hidden');
            
            // Prevent body scrolling
            document.body.style.overflow = 'hidden';
        }
        
        // Close delete modal when clicking cancel or overlay
        if (cancelDelete) {
            cancelDelete.addEventListener('click', closeDeleteModal);
        }
        
        if (modalOverlay) {
            modalOverlay.addEventListener('click', closeDeleteModal);
        }
        
        // Close grade modal when clicking cancel, close button or overlay
        if (closeGradeModal) {
            closeGradeModal.addEventListener('click', closeGradeModal);
        }
        
        if (cancelGrade) {
            cancelGrade.addEventListener('click', closeGradeModal);
        }
        
        if (gradeModalOverlay) {
            gradeModalOverlay.addEventListener('click', closeGradeModal);
        }
        
        // Function to close delete modal
        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        // Function to close grade modal
        function closeGradeModal() {
            gradeModal.classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        // Close modals with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                if (!deleteModal.classList.contains('hidden')) {
                    closeDeleteModal();
                }
                if (!gradeModal.classList.contains('hidden')) {
                    closeGradeModal();
                }
            }
        });
        
        // Search functionality
        const searchInput = document.getElementById('search-students');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchValue = this.value.toLowerCase();
                const rows = document.querySelectorAll('.submission-row');
                
                rows.forEach(row => {
                    const studentName = row.querySelector('.student-name').textContent.toLowerCase();
                    if (studentName.includes(searchValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
        
        // Initialize the chart
        const ctx = document.getElementById('submissionChart');
        if (ctx) {
            const submitted = {{ $assignment->submissions->count() }};
            const notSubmitted = {{ $assignment->classroom->students->count() - $assignment->submissions->count() }};
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Mengumpulkan', 'Belum Mengumpulkan'],
                    datasets: [{
                        data: [submitted, notSubmitted],
                        backgroundColor: ['#10B981', '#F87171'],
                        borderWidth: 0,
                        hoverOffset: 5
                    }]
                },
                options: {
                    cutout: '70%',
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.formattedValue || '';
                                    return `${label}: ${value}`;
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush