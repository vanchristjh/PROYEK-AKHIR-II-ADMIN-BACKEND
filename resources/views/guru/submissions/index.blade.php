@extends('layouts.dashboard')

@section('title', 'Kelola Pengumpulan Tugas')

@section('header', 'Pengumpulan Tugas')

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
    <div class="mb-6">
        <a href="{{ route('guru.assignments.show', $assignment->id) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition-colors">
            <i class="fas fa-chevron-left mr-2 text-sm"></i>
            <span>Kembali ke Detail Tugas</span>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-md animate-fade-in">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Header with Assignment Info -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 mb-6">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white">
            <div class="flex items-start justify-between">
                <div class="flex items-start">
                    <div class="bg-blue-100 text-blue-600 p-3 rounded-full mr-4">
                        <i class="fas fa-file-alt text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">{{ $assignment->title }}</h1>
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-book mr-1"></i>
                                <span>{{ $assignment->subject->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-users mr-1"></i>
                                <span>{{ $assignment->classroom->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar-day mr-1"></i>
                                <span>Deadline: {{ $assignment->deadline->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                        <i class="fas fa-star mr-1"></i> Nilai Maksimal: {{ $assignment->max_score }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Submission Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6 animate-fade-in">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center mb-2">
                <div class="p-2 rounded-full bg-indigo-100 text-indigo-600 mr-3">
                    <i class="fas fa-users"></i>
                </div>
                <h4 class="font-medium text-gray-700">Total Siswa</h4>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $totalStudents }}</p>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center mb-2">
                <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h4 class="font-medium text-gray-700">Sudah Mengumpulkan</h4>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $submissions->count() }}</p>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center mb-2">
                <div class="p-2 rounded-full bg-red-100 text-red-600 mr-3">
                    <i class="fas fa-times-circle"></i>
                </div>
                <h4 class="font-medium text-gray-700">Belum Mengumpulkan</h4>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $totalStudents - $submissions->count() }}</p>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center mb-2">
                <div class="p-2 rounded-full bg-yellow-100 text-yellow-600 mr-3">
                    <i class="fas fa-star"></i>
                </div>
                <h4 class="font-medium text-gray-700">Sudah Dinilai</h4>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $submissions->where('score', '!=', null)->count() }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 mb-6 animate-fade-in">
        <div class="p-4">
            <form id="filter-form" action="{{ route('guru.submissions.index', $assignment->id) }}" method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-grow min-w-[200px]">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Siswa</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nama atau NIS siswa..." 
                            class="pl-10 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Filter Status</label>
                    <select name="status" id="status" class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="" {{ request('status') === null ? 'selected' : '' }}>Semua Status</option>
                        <option value="graded" {{ request('status') === 'graded' ? 'selected' : '' }}>Sudah Dinilai</option>
                        <option value="ungraded" {{ request('status') === 'ungraded' ? 'selected' : '' }}>Belum Dinilai</option>
                        <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Terlambat</option>
                        <option value="ontime" {{ request('status') === 'ontime' ? 'selected' : '' }}>Tepat Waktu</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('guru.submissions.index', $assignment->id) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        <i class="fas fa-times mr-2"></i> Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end mb-4 animate-fade-in">
        <div class="flex space-x-3">
            <button id="mass-grade-btn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-star mr-2"></i> Penilaian Massal
            </button>
            <a href="{{ route('guru.submissions.export', $assignment->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                <i class="fas fa-file-export mr-2"></i> Export Nilai
            </a>
            <button id="btn-toggle-nosubmit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                <i class="fas fa-eye mr-2"></i> Tampilkan Belum Mengumpulkan
            </button>
        </div>
    </div>

    <!-- Submissions Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 mb-6 animate-fade-in">
        @if($submissions->isEmpty() && !request('show_nosubmit'))
            <div class="p-8 text-center">
                <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 text-yellow-600 mb-4">
                    <i class="fas fa-exclamation-circle text-2xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-800 mb-1">Belum ada pengumpulan</h4>
                <p class="text-gray-500 mb-4">Belum ada siswa yang mengumpulkan tugas ini.</p>
                <button id="btn-show-nosubmit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                    <i class="fas fa-eye mr-2"></i> Tampilkan Semua Siswa
                </button>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 styled-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <input type="checkbox" id="select-all" class="custom-checkbox">
                                    <span class="ml-2">Siswa</span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Pengumpulan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($submissions as $submission)
                            <tr class="hover:bg-gray-50 {{ !$submission->submitted ? 'bg-red-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($submission->submitted)
                                            <input type="checkbox" name="submission_ids[]" value="{{ $submission->id }}" class="submission-checkbox custom-checkbox" data-student="{{ $submission->student->name ?? 'Unknown' }}">
                                        @else
                                            <div class="h-4 w-4"></div>
                                        @endif
                                        <div class="ml-2 flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-500">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $submission->student->name ?? 'Unknown' }}</div>
                                                <div class="text-xs text-gray-500">NIS: {{ $submission->student->nis ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($submission->submitted)
                                        <div class="text-sm text-gray-500">
                                            {{ $submission->submitted_at->format('d M Y, H:i') }}
                                            @if($submission->submitted_at->gt($assignment->deadline))
                                                <span class="inline-flex items-center ml-1 px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-clock mr-1"></i> Terlambat
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i> Tidak Mengumpulkan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(!$submission->submitted)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i> Tidak Ada
                                        </span>
                                    @elseif($submission->score !== null)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Sudah Dinilai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1.5"></i> Belum Dinilai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($submission->submitted && $submission->score !== null)
                                        <span class="font-medium text-green-600">{{ $submission->score }}</span>
                                    @elseif(!$submission->submitted)
                                        <span class="font-medium text-red-600">0</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>                                        
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        @if($submission->submitted)
                                            <a href="{{ route('guru.submissions.download', ['assignment' => $assignment->id, 'submission' => $submission->id]) }}" class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 p-1.5 rounded-md transition-colors" title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <a href="{{ route('guru.submissions.show', ['assignment' => $assignment->id, 'submission' => $submission->id]) }}" class="text-yellow-600 hover:text-yellow-900 bg-yellow-100 hover:bg-yellow-200 p-1.5 rounded-md transition-colors" title="{{ $submission->score !== null ? 'Edit Nilai' : 'Beri Nilai' }}">
                                                <i class="fas fa-{{ $submission->score !== null ? 'edit' : 'star' }}"></i>
                                            </a>
                                        @else
                                            <form action="{{ route('guru.submissions.zero', ['assignment' => $assignment->id, 'student' => $submission->student->id]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 p-1.5 rounded-md transition-colors" title="Beri Nilai 0" onclick="return confirm('Beri nilai 0 untuk siswa ini karena tidak mengumpulkan?')">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($submissions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $submissions->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- Mass Grading Modal -->
    <div class="fixed inset-0 z-50 flex items-center justify-center hidden" id="mass-grade-modal">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" id="modal-overlay"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full mx-auto shadow-xl z-50 overflow-hidden transform transition-all">
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button type="button" id="close-modal" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="sr-only">Close</span>
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="bg-white px-6 py-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-star text-green-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Penilaian Massal</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="selected-count">
                                0 siswa dipilih untuk penilaian massal.
                            </p>
                        </div>
                    </div>
                </div>
                <form id="mass-grade-form" action="{{ route('guru.submissions.mass-grade', $assignment->id) }}" method="POST" class="mt-4">
                    @csrf
                    <div id="selected-submissions"></div>
                    
                    <div class="mb-4">
                        <label for="mass_score" class="block text-sm font-medium text-gray-700 mb-1">Nilai <span class="text-red-500">*</span></label>
                        <div class="flex items-center">
                            <input type="number" name="mass_score" id="mass_score" min="0" max="{{ $assignment->max_score }}" 
                                value="{{ old('mass_score', '') }}" 
                                class="mr-2 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            <span class="text-gray-500 text-sm">/ {{ $assignment->max_score }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Nilai maksimum: {{ $assignment->max_score }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <label for="mass_feedback" class="block text-sm font-medium text-gray-700 mb-1">Feedback (Opsional)</label>
                        <textarea name="mass_feedback" id="mass_feedback" rows="3" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            >{{ old('mass_feedback', '') }}</textarea>
                    </div>
                </form>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="submit-mass-grade" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Simpan Nilai
                </button>
                <button type="button" id="cancel-mass-grade" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const massGradeBtn = document.getElementById('mass-grade-btn');
        const closeModal = document.getElementById('close-modal');
        const modalOverlay = document.getElementById('modal-overlay');
        const cancelMassGrade = document.getElementById('cancel-mass-grade');
        const submitMassGrade = document.getElementById('submit-mass-grade');
        const massGradeForm = document.getElementById('mass-grade-form');
        const massScoreInput = document.getElementById('mass_score');
        const selectedSubmissionsContainer = document.getElementById('selected-submissions');
        const massGradeModal = document.getElementById('mass-grade-modal');
        const selectAllCheckbox = document.getElementById('select-all');
        const submissionCheckboxes = document.querySelectorAll('.submission-checkbox');
        
        function getCheckedSubmissions() {
            return Array.from(document.querySelectorAll('.submission-checkbox:checked')).map(cb => ({
                id: cb.value,
                student: cb.dataset.student
            }));
        }
        
        function openMassGradeModal() {
            massGradeModal.classList.remove('hidden');
            
            // Add selected submissions to the form
            const selectedSubmissions = getCheckedSubmissions();
            
            // Clear previous selections
            selectedSubmissionsContainer.innerHTML = '';
            
            // Add each selected submission to the container and form
            selectedSubmissions.forEach(submission => {
                // Add to visible list
                const listItem = document.createElement('li');
                listItem.className = 'py-2 border-b border-gray-200 last:border-0';
                listItem.textContent = submission.student;
                selectedSubmissionsContainer.appendChild(listItem);
                
                // Add to form as hidden input
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'submission_ids[]';
                hiddenInput.value = submission.id;
                massGradeForm.appendChild(hiddenInput);
            });
        }
        
        function closeMassGradeModal() {
            massGradeModal.classList.add('hidden');
        }
        
        if (massGradeBtn) {
            massGradeBtn.addEventListener('click', function() {
                const checkedSubmissions = getCheckedSubmissions();
                
                if (checkedSubmissions.length === 0) {
                    alert('Silakan pilih minimal satu pengumpulan untuk penilaian massal.');
                    return;
                }
                
                // Open modal
                openMassGradeModal();
            });
        }
        
        // Close modal functions
        if (closeModal) closeModal.addEventListener('click', closeMassGradeModal);
        if (modalOverlay) modalOverlay.addEventListener('click', closeMassGradeModal);
        if (cancelMassGrade) cancelMassGrade.addEventListener('click', closeMassGradeModal);
        
        // Submit mass grade form
        if (submitMassGrade && massScoreInput && massGradeForm) {
            submitMassGrade.addEventListener('click', function() {
                const score = massScoreInput.value.trim();
                
                if (!score) {
                    alert('Mohon masukkan nilai untuk tugas ini.');
                    massScoreInput.focus();
                    return;
                }
                
                const numScore = parseInt(score);
                if (isNaN(numScore)) {
                    alert('Nilai harus berupa angka.');
                    massScoreInput.focus();
                    return;
                }
                
                const maxScore = parseInt(massScoreInput.dataset.max || 100);
                if (numScore < 0 || numScore > maxScore) {
                    alert(`Nilai harus berada di antara 0 dan ${maxScore}.`);
                    massScoreInput.focus();
                    return;
                }
                
                massGradeForm.submit();
            });
        }
        
        // Select all functionality
        if (selectAllCheckbox && submissionCheckboxes.length > 0) {
            selectAllCheckbox.addEventListener('change', function() {
                submissionCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                
                // Update mass grade button state
                if (massGradeBtn) {
                    const anyChecked = Array.from(submissionCheckboxes).some(cb => cb.checked);
                    massGradeBtn.disabled = !anyChecked;
                    massGradeBtn.classList.toggle('opacity-50', !anyChecked);
                    massGradeBtn.classList.toggle('cursor-not-allowed', !anyChecked);
                }
            });
        }
        
        // Update "select all" when individual checkboxes change
        submissionCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (selectAllCheckbox) {
                    const allChecked = Array.from(submissionCheckboxes).every(cb => cb.checked);
                    const someChecked = Array.from(submissionCheckboxes).some(cb => cb.checked);
                    
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = someChecked && !allChecked;
                }
                
                // Enable/disable mass grade button
                if (massGradeBtn) {
                    const someChecked = Array.from(submissionCheckboxes).some(cb => cb.checked);
                    massGradeBtn.disabled = !someChecked;
                    massGradeBtn.classList.toggle('opacity-50', !someChecked);
                    massGradeBtn.classList.toggle('cursor-not-allowed', !someChecked);
                }
            });
        });
        
        // Initialize mass grade button state
        if (massGradeBtn) {
            const someChecked = Array.from(submissionCheckboxes).some(cb => cb.checked);
            massGradeBtn.disabled = !someChecked;
            massGradeBtn.classList.toggle('opacity-50', !someChecked);
            massGradeBtn.classList.toggle('cursor-not-allowed', !someChecked);
        }
        
        // Filter functionality
        const filterForm = document.getElementById('filter-form');
        const filterInputs = document.querySelectorAll('#filter-form select');
        
        if (filterForm && filterInputs.length > 0) {
            filterInputs.forEach(input => {
                input.addEventListener('change', function() {
                    filterForm.submit();
                });
            });
        }
    });
</script>

@push('styles')
<style>
    /* Improved table styles */
    .styled-table th {
        position: relative;
    }
    
    .styled-table th.sortable::after {
        content: '';
        position: absolute;
        right: 0.5rem;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 4px solid #a0aec0;
    }
    
    .styled-table th.sortable.asc::after {
        border-top: none;
        border-bottom: 4px solid #4a5568;
    }
    
    .styled-table th.sortable.desc::after {
        border-top: 4px solid #4a5568;
    }
    
    /* Checkbox styles */
    .custom-checkbox {
        appearance: none;
        -webkit-appearance: none;
        width: 1.2em;
        height: 1.2em;
        border-radius: 0.25em;
        border: 2px solid #d1d5db;
        outline: none;
        transition: all 0.2s ease;
        position: relative;
    }
    
    .custom-checkbox:checked {
        border-color: #4f46e5;
        background-color: #4f46e5;
    }
    
    .custom-checkbox:checked::after {
        content: '';
        position: absolute;
        left: 0.3em;
        top: 0.15em;
        width: 0.4em;
        height: 0.6em;
        border-right: 2px solid white;
        border-bottom: 2px solid white;
        transform: rotate(45deg);
    }
    
    .custom-checkbox:indeterminate {
        border-color: #4f46e5;
        background-color: #4f46e5;
    }
    
    .custom-checkbox:indeterminate::after {
        content: '';
        position: absolute;
        left: 0.2em;
        top: 0.5em;
        width: 0.6em;
        height: 2px;
        background-color: white;
    }
</style>
@endpush
