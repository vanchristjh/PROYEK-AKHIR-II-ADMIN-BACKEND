@extends('layouts.dashboard')

@section('title', 'Penilaian Tugas')

@section('header', 'Penilaian Tugas')

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
        <a href="{{ route('guru.assignments.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-green-700/50 transition-all duration-200">
                <i class="fas fa-tasks text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Tugas</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.grades.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-2.5 group relative text-white">
            <div class="p-1.5 rounded-lg bg-amber-700 transition-all duration-200">
                <i class="fas fa-star text-lg w-5 h-5 flex items-center justify-center text-white"></i>
            </div>
            <span class="ml-3">Penilaian</span>
            <span class="absolute inset-y-0 left-0 w-1 bg-amber-400 rounded-tr-md rounded-br-md"></span>
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
    <!-- Header with enhanced animation -->
    <div class="bg-gradient-to-r from-amber-500 to-yellow-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-star text-9xl"></i>
        </div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute right-1/3 -top-12 w-36 h-36 bg-yellow-300/20 rounded-full blur-3xl"></div>
        <div class="relative z-10">
            <div class="flex items-center">
                <div class="bg-white/20 p-2 rounded-lg shadow-inner backdrop-blur-sm mr-4">
                    <i class="fas fa-star text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold mb-1">Penilaian Tugas Siswa</h2>
                    <p class="text-amber-100">Kelola nilai dari tugas yang sudah dikumpulkan siswa.</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md animate-fade-in">
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

    <!-- Filter section -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6 border border-gray-100/50">
        <form action="{{ route('guru.grades.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-grow min-w-[200px]">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                    <option value="">Semua Status</option>
                    <option value="graded" {{ request('status') == 'graded' ? 'selected' : '' }}>Sudah Dinilai</option>
                    <option value="ungraded" {{ request('status') == 'ungraded' ? 'selected' : '' }}>Belum Dinilai</option>
                </select>
            </div>
            <div class="flex-grow min-w-[200px]">
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                <select name="subject" id="subject" class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach(auth()->user()->teacherSubjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-grow min-w-[200px]">
                <label for="classroom" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                <select name="classroom" id="classroom" class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                    <option value="">Semua Kelas</option>
                    @foreach(auth()->user()->teachingClassrooms ?? [] as $classroom)
                        <option value="{{ $classroom->id }}" {{ request('classroom') == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                <i class="fas fa-filter mr-2"></i> Filter
            </button>
            @if(request()->anyFilled(['status', 'subject', 'classroom']))
                <a href="{{ route('guru.grades.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-times mr-2"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas & Mapel</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kumpul</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($submissions ?? [] as $submission)
                        <tr class="hover:bg-gray-50 transition-colors animate-item" style="animation-delay: {{ $loop->index * 50 }}ms">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600">
                                        {{ strtoupper(substr($submission->student->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $submission->student->name ?? 'Unknown' }}</div>
                                        <div class="text-xs text-gray-500">NIS: {{ $submission->student->id_number ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">{{ $submission->assignment->title }}</div>
                                <div class="text-xs text-gray-500">
                                    @if($submission->file_path)
                                        <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-file-alt mr-1"></i> Lihat Berkas
                                        </a>
                                    @else
                                        <span class="text-gray-400"><i class="fas fa-times-circle mr-1"></i> Tidak ada berkas</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $submission->assignment->classroom->name }}</div>
                                <div class="text-xs text-gray-500">{{ $submission->assignment->subject->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $submission->submitted_at->format('d M Y, H:i') }}</div>
                                @if($submission->submitted_at != $submission->created_at)
                                    <div class="text-xs text-gray-400">
                                        <i class="fas fa-sync-alt mr-1"></i> Diperbarui
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($submission->isGraded())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1.5"></i> Nilai: {{ $submission->score }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1.5"></i> Belum Dinilai
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('guru.grades.edit', $submission->id) }}" class="text-yellow-600 hover:text-yellow-900 bg-yellow-100 hover:bg-yellow-200 p-1.5 rounded-md transition-colors">
                                    <i class="fas fa-{{ $submission->isGraded() ? 'edit' : 'star' }}"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                <div class="py-8">
                                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-4">
                                        <i class="fas fa-star text-yellow-500 text-xl"></i>
                                    </div>
                                    <h3 class="text-base font-medium text-gray-900 mb-1">Belum ada tugas untuk dinilai</h3>
                                    <p class="text-sm text-gray-500">Tugas yang dikumpulkan siswa akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($submissions) && $submissions->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $submissions->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true" role="dialog">
        <div id="modalOverlay" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                Hapus Nilai
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" id="modal-description">
                                    Apakah Anda yakin ingin menghapus nilai ini? Tindakan ini tidak dapat dibatalkan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                            Hapus
                        </button>
                        <button type="button" id="cancelDelete" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
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
    
    .animate-pulse-badge {
        animation: pulse-badge 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse-badge {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.6;
        }
    }
    
    .notification-dot {
        position: absolute;
        top: -2px;
        right: -2px;
        width: 8px;
        height: 8px;
        background-color: #ef4444;
        border-radius: 50%;
    }
    
    .grade-input:focus-within {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 1px rgba(139, 92, 246, 0.2);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const subjectSelect = document.getElementById('subject_id');
        const classroomSelect = document.getElementById('classroom_id');
        const studentSearch = document.getElementById('student_search');
        const tableBody = document.querySelector('.students-table tbody');
        const noDataRow = document.getElementById('no-data-row');
        const loadingRow = document.getElementById('loading-row');
        
        // Function to show delete confirmation
        window.confirmDelete = function(url, studentName, assignmentTitle) {
            const deleteModal = document.getElementById('deleteModal');
            const deleteForm = document.getElementById('deleteForm');
            const modalDescription = document.getElementById('modal-description');
            const modalOverlay = document.getElementById('modalOverlay');
            const cancelDelete = document.getElementById('cancelDelete');
            
            // Update modal content and form action
            modalDescription.textContent = `Apakah Anda yakin ingin menghapus nilai untuk ${studentName} pada tugas ${assignmentTitle || 'ini'}? Tindakan ini tidak dapat dibatalkan.`;
            deleteForm.action = url;
            
            // Show modal
            deleteModal.classList.remove('hidden');
            
            // Setup closing handlers
            modalOverlay.addEventListener('click', closeModal);
            cancelDelete.addEventListener('click', closeModal);
            
            function closeModal() {
                deleteModal.classList.add('hidden');
            }
        };
        
        // When subject changes, update classrooms
        subjectSelect.addEventListener('change', function() {
            const subjectId = this.value;
            
            // Update classrooms dropdown
            if (subjectId) {
                // Clear classroom select
                classroomSelect.innerHTML = '<option value="">Pilih Kelas</option>';
                classroomSelect.disabled = true;
                
                // Show loading option
                const loadingOption = document.createElement('option');
                loadingOption.textContent = 'Memuat kelas...';
                loadingOption.disabled = true;
                classroomSelect.appendChild(loadingOption);
                
                // Get classrooms for this subject
                fetch(`/guru/subjects/${subjectId}/classrooms`)
                    .then(response => response.json())
                    .then(data => {
                        // Remove loading option
                        classroomSelect.removeChild(loadingOption);
                        classroomSelect.disabled = false;
                        
                        if (data.length === 0) {
                            const noClassOption = document.createElement('option');
                            noClassOption.textContent = 'Tidak ada kelas untuk mata pelajaran ini';
                            noClassOption.disabled = true;
                            classroomSelect.appendChild(noClassOption);
                        } else {
                            data.forEach(classroom => {
                                const option = document.createElement('option');
                                option.value = classroom.id;
                                option.textContent = classroom.name;
                                classroomSelect.appendChild(option);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error loading classrooms:', error);
                        classroomSelect.innerHTML = '<option value="">Error memuat kelas</option>';
                    });
            } else {
                classroomSelect.innerHTML = '<option value="">Pilih Kelas</option>';
                classroomSelect.disabled = true;
                
                // Clear student table
                if (tableBody) {
                    tableBody.innerHTML = '';
                    if (noDataRow) {
                        noDataRow.style.display = 'table-row';
                    }
                }
            }
        });
        
        // When classroom changes, load students
        classroomSelect.addEventListener('change', function() {
            loadStudents();
        });
        
        // Search function with debounce
        let searchTimeout;
        if (studentSearch) {
            studentSearch.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    loadStudents();
                }, 300);
            });
        }
        
        function loadStudents() {
            const classroomId = classroomSelect.value;
            const subjectId = subjectSelect.value;
            const searchQuery = studentSearch ? studentSearch.value : '';
            
            if (classroomId && subjectId) {
                // Show loading
                if (loadingRow) {
                    loadingRow.style.display = 'table-row';
                }
                if (noDataRow) {
                    noDataRow.style.display = 'none';
                }
                
                // Clear existing data
                if (tableBody) {
                    tableBody.innerHTML = '';
                }
                
                // Fetch data
                const url = `/guru/grades/get-students?classroom_id=${classroomId}&subject_id=${subjectId}${searchQuery ? '&search=' + encodeURIComponent(searchQuery) : ''}`;
                
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        // Hide loading
                        if (loadingRow) {
                            loadingRow.style.display = 'none';
                        }
                        
                        // Process data
                        if (data.length === 0) {
                            if (noDataRow) {
                                noDataRow.style.display = 'table-row';
                            }
                        } else {
                            data.forEach(student => {
                                const row = document.createElement('tr');
                                row.className = 'hover:bg-gray-50';
                                
                                let gradesHtml = '';
                                if (student.assignments && student.assignments.length > 0) {
                                    gradesHtml = student.assignments.map(assignment => {
                                        return `
                                            <div class="mb-2 p-2 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors">
                                                <div class="flex justify-between items-center mb-1.5">
                                                    <div class="text-sm font-medium text-gray-700">${assignment.title}</div>
                                                    <div class="flex items-center space-x-1">
                                                        <span class="px-2 py-0.5 text-xs rounded-md ${assignment.grade ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                                            ${assignment.grade ? assignment.grade.score : 'Belum dinilai'}
                                                        </span>
                                                        ${assignment.grade ? `
                                                            <form onsubmit="event.preventDefault(); confirmDelete('${assignment.grade.delete_url}', '${student.name}', '${assignment.title}')">
                                                                <button type="submit" class="text-red-500 hover:text-red-700 ml-1">
                                                                    <i class="fas fa-times-circle"></i>
                                                                </button>
                                                            </form>
                                                        ` : ''}
                                                    </div>
                                                </div>
                                                <div class="flex items-center">
                                                    <div class="flex-grow">
                                                        <input type="number" name="score[${assignment.id}][${student.id}]" 
                                                            placeholder="0-100" min="0" max="100" step="1" 
                                                            class="grade-input w-full text-sm border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500" 
                                                            value="${assignment.grade ? assignment.grade.score : ''}">
                                                    </div>
                                                    <button type="button" class="save-grade-btn ml-2 px-2 py-1 bg-purple-600 text-white rounded-md text-xs hover:bg-purple-700 transition-colors"
                                                        data-assignment-id="${assignment.id}" 
                                                        data-student-id="${student.id}">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        `;
                                    }).join('');
                                } else {
                                    gradesHtml = '<p class="text-sm text-gray-500 italic">Tidak ada tugas</p>';
                                }
                                
                                row.innerHTML = `
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">${student.name}</div>
                                                <div class="text-xs text-gray-500">${student.nis || 'NIS tidak tersedia'}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            ${gradesHtml}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">${student.average || '-'}</div>
                                        <div class="text-xs text-gray-500">Rata-rata nilai</div>
                                    </td>
                                `;
                                
                                tableBody.appendChild(row);
                            });
                            
                            // Add event listeners to save buttons
                            document.querySelectorAll('.save-grade-btn').forEach(button => {
                                button.addEventListener('click', saveGrade);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error loading students:', error);
                        if (loadingRow) {
                            loadingRow.style.display = 'none';
                        }
                        if (noDataRow) {
                            const tdElement = noDataRow.querySelector('td');
                            if (tdElement) {
                                tdElement.textContent = 'Terjadi kesalahan saat memuat data';
                            }
                            noDataRow.style.display = 'table-row';
                        }
                    });
            } else {
                // Clear student table if no classroom or subject selected
                if (tableBody) {
                    tableBody.innerHTML = '';
                }
                if (noDataRow) {
                    const tdElement = noDataRow.querySelector('td');
                    if (tdElement) {
                        tdElement.textContent = 'Pilih mata pelajaran dan kelas terlebih dahulu';
                    }
                    noDataRow.style.display = 'table-row';
                }
                if (loadingRow) {
                    loadingRow.style.display = 'none';
                }
            }
        }
        
        // Save grade function
        function saveGrade() {
            const assignmentId = this.dataset.assignmentId;
            const studentId = this.dataset.studentId;
            const scoreInput = document.querySelector(`input[name="score[${assignmentId}][${studentId}]"]`);
            const score = scoreInput.value.trim();
            
            if (!score) {
                alert('Nilai tidak boleh kosong');
                return;
            }
            
            if (isNaN(score) || score < 0 || score > 100) {
                alert('Nilai harus berupa angka antara 0-100');
                return;
            }
            
            // Show saving indicator
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.disabled = true;
            
            // Save data
            fetch('/guru/grades/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    assignment_id: assignmentId,
                    student_id: studentId,
                    score: score
                })
            })
            .then(response => response.json())
            .then(data => {
                // Restore button
                this.innerHTML = originalText;
                this.disabled = false;
                
                if (data.success) {
                    // Show success indicator briefly
                    this.innerHTML = '<i class="fas fa-check"></i>';
                    this.classList.remove('bg-purple-600', 'hover:bg-purple-700');
                    this.classList.add('bg-green-600', 'hover:bg-green-700');
                    
                    // Restore after a delay
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.remove('bg-green-600', 'hover:bg-green-700');
                        this.classList.add('bg-purple-600', 'hover:bg-purple-700');
                    }, 1500);
                    
                    // Update the displayed score if needed
                    const scoreDisplay = this.closest('.mb-2').querySelector('.text-xs');
                    if (scoreDisplay) {
                        scoreDisplay.textContent = score;
                        scoreDisplay.classList.remove('bg-gray-100', 'text-gray-800');
                        scoreDisplay.classList.add('bg-green-100', 'text-green-800');
                    }
                    
                    // Reload student data to update average
                    loadStudents();
                } else {
                    // Show error indicator briefly
                    this.innerHTML = '<i class="fas fa-times"></i>';
                    this.classList.remove('bg-purple-600', 'hover:bg-purple-700');
                    this.classList.add('bg-red-600', 'hover:bg-red-700');
                    
                    // Restore after a delay
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.remove('bg-red-600', 'hover:bg-red-700');
                        this.classList.add('bg-purple-600', 'hover:bg-purple-700');
                    }, 1500);
                    
                    // Show error message
                    alert(data.message || 'Terjadi kesalahan saat menyimpan nilai');
                }
            })
            .catch(error => {
                console.error('Error saving grade:', error);
                
                // Restore button
                this.innerHTML = originalText;
                this.disabled = false;
                
                // Show error indicator briefly
                this.innerHTML = '<i class="fas fa-times"></i>';
                this.classList.remove('bg-purple-600', 'hover:bg-purple-700');
                this.classList.add('bg-red-600', 'hover:bg-red-700');
                
                // Restore after a delay
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.classList.remove('bg-red-600', 'hover:bg-red-700');
                    this.classList.add('bg-purple-600', 'hover:bg-purple-700');
                }, 1500);
                
                alert('Terjadi kesalahan saat menyimpan nilai');
            });
        }
        
        // Initialize if pre-selected values exist
        if (subjectSelect.value) {
            subjectSelect.dispatchEvent(new Event('change'));
            
            // If classroom already selected, load students
            if (classroomSelect.value) {
                setTimeout(() => {
                    loadStudents();
                }, 300);
            }
        }
    });
</script>
@endpush
