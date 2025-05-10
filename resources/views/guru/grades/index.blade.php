@extends('layouts.dashboard')

@section('title', 'Manajemen Penilaian')

@section('header', 'Manajemen Penilaian')

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
@endsection

@section('content')
    <!-- Header with enhanced animation -->
    <div class="bg-gradient-to-r from-amber-500 to-yellow-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-star text-9xl"></i>
        </div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute right-1/3 -top-12 w-36 h-36 bg-yellow-300/20 rounded-full blur-3xl"></div>
        <div class="relative z-10 flex justify-between items-center">
            <div>
                <div class="flex items-center">
                    <div class="bg-white/20 p-2 rounded-lg shadow-inner backdrop-blur-sm mr-4">
                        <i class="fas fa-star text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold mb-1">Manajemen Penilaian</h2>
                        <p class="text-amber-100">Kelola nilai dari tugas dan penilaian langsung untuk siswa.</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('guru.grades.create') }}" class="px-5 py-2.5 bg-white text-amber-700 rounded-lg shadow-md hover:bg-amber-50 transition-colors flex items-center">
                <i class="fas fa-plus mr-2"></i> Buat Penilaian
            </a>
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
                    @foreach($subjects as $subject)
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
                    @foreach($classrooms as $classroom)
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

    <!-- Tabs for different grade types -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button type="button" class="tab-button active border-amber-500 text-amber-600 py-4 px-1 border-b-2 font-medium text-sm" data-tab="submissions-tab">
                    Penilaian Tugas <span class="ml-2 bg-yellow-100 text-yellow-900 py-0.5 px-2 rounded-full text-xs">{{ $submissions->total() }}</span>
                </button>
                <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 border-b-2 font-medium text-sm" data-tab="direct-tab">
                    Penilaian Langsung <span class="ml-2 bg-purple-100 text-purple-900 py-0.5 px-2 rounded-full text-xs">{{ $directGrades->total() }}</span>
                </button>
            </nav>
        </div>
    </div>

    <!-- Tab Content: Submission-based Grades -->
    <div id="submissions-tab" class="tab-content">
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
                        @forelse ($submissions as $submission)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-800 font-medium">
                                            {{ substr($submission->student->name, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $submission->student->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $submission->student->nis ?? 'NIS tidak tersedia' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-medium">{{ $submission->assignment->title }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($submission->assignment->description, 30) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 mb-1">
                                            {{ $submission->assignment->classroom->name }}
                                        </span>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $submission->assignment->subject->name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $submission->submitted_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $submission->submitted_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($submission->isGraded())
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Dinilai ({{ $submission->score }})
                                        </span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i> Belum dinilai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <a href="{{ route('guru.submissions.show', ['assignment' => $submission->assignment_id, 'submission' => $submission->id]) }}" 
                                       class="text-yellow-600 hover:text-yellow-900 bg-yellow-100 hover:bg-yellow-200 p-1.5 rounded-md transition-colors">
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
                                        <p class="text-sm text-gray-500">Belum ada pengumpulan tugas dari siswa atau semua tugas telah dinilai.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                {{ $submissions->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- Tab Content: Direct Assessment Grades -->
    <div id="direct-tab" class="tab-content hidden">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50">
            <div class="flex justify-between items-center p-5 bg-gray-50">
                <h3 class="font-bold text-gray-700">Penilaian Langsung</h3>
                <a href="{{ route('guru.grades.create') }}" class="flex items-center text-purple-600 hover:text-purple-900">
                    <i class="fas fa-plus-circle mr-1"></i> Tambah Penilaian Baru
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas & Mapel</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe Penilaian</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal & Semester</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($directGrades as $grade)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-800 font-medium">
                                            {{ substr($grade->student->name, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $grade->student->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $grade->student->nis ?? 'NIS tidak tersedia' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 mb-1">
                                            {{ $grade->classroom->name }}
                                        </span>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $grade->subject->name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        {{ ucfirst($grade->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ $grade->score }} / {{ $grade->max_score }}</div>
                                    <div class="text-xs text-gray-500">{{ $grade->percentage }}% ({{ $grade->letter_grade }})</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $grade->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $grade->semester }} / {{ $grade->academic_year }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-3">
                                        <a href="{{ route('guru.grades.edit', $grade->id) }}" class="text-purple-600 hover:text-purple-900 bg-purple-100 hover:bg-purple-200 p-1.5 rounded-md transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('guru.grades.destroy', $grade->id) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus penilaian ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 p-1.5 rounded-md transition-colors">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                    <div class="py-8">
                                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-purple-100 mb-4">
                                            <i class="fas fa-clipboard-list text-purple-500 text-xl"></i>
                                        </div>
                                        <h3 class="text-base font-medium text-gray-900 mb-1">Belum ada penilaian langsung</h3>
                                        <p class="text-sm text-gray-500 mb-4">Tambahkan penilaian untuk kuis, ujian, atau jenis penilaian lainnya.</p>
                                        <a href="{{ route('guru.grades.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                            <i class="fas fa-plus mr-2"></i> Buat Penilaian Baru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                {{ $directGrades->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Modal for Grades -->
    <div id="deleteModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div id="modalOverlay" class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Hapus Penilaian
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" id="modal-description">
                                    Apakah Anda yakin ingin menghapus penilaian ini? Tindakan ini tidak dapat dibatalkan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Hapus
                        </button>
                    </form>
                    <button id="cancelDelete" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
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
    
    .avatar-initial {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #fff;
        border-radius: 50%;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');
        
        // Tab switching functionality
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const tabId = button.getAttribute('data-tab');
                
                // Remove active class from all tabs
                tabButtons.forEach(btn => {
                    btn.classList.remove('active', 'border-amber-500', 'text-amber-600');
                    btn.classList.add('border-transparent', 'text-gray-500');
                });
                
                // Add active class to current tab
                button.classList.remove('border-transparent', 'text-gray-500');
                button.classList.add('active', 'border-amber-500', 'text-amber-600');
                
                // Hide all tab content
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });
                
                // Show selected tab content
                document.getElementById(tabId).classList.remove('hidden');
            });
        });
        
        // Function to show delete confirmation
        window.confirmDelete = function(url, studentName, gradeName) {
            const deleteModal = document.getElementById('deleteModal');
            const deleteForm = document.getElementById('deleteForm');
            const modalDescription = document.getElementById('modal-description');
            const modalOverlay = document.getElementById('modalOverlay');
            const cancelDelete = document.getElementById('cancelDelete');
            
            // Update modal content and form action
            modalDescription.textContent = `Apakah Anda yakin ingin menghapus penilaian untuk ${studentName || 'siswa ini'}${gradeName ? ' pada ' + gradeName : ''}? Tindakan ini tidak dapat dibatalkan.`;
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
    });
</script>
@endpush
