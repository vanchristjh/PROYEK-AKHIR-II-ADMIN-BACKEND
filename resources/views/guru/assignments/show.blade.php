@extends('layouts.dashboard')

@section('title', $assignment->title)

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
    <div class="mb-6">
        <a href="{{ route('guru.assignments.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition-colors">
            <i class="fas fa-chevron-left mr-2 text-sm"></i>
            <span>Kembali ke Daftar Tugas</span>
        </a>
    </div>

    <!-- Assignment Header -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 mb-6">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-green-50 to-white">
            <div class="flex items-start justify-between">
                <div class="flex items-start">
                    <div class="bg-green-100 text-green-600 p-3 rounded-full mr-4">
                        <i class="fas fa-file-alt text-xl"></i>
                    </div>
                    <div>
                        <div class="flex items-center mb-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                <i class="fas fa-book mr-1"></i> {{ $assignment->subject->name ?? 'N/A' }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200 ml-2">
                                <i class="fas fa-users mr-1"></i> {{ $assignment->classroom->name ?? 'N/A' }}
                            </span>
                        </div>
                        <h1 class="text-xl font-bold text-gray-900 mb-2">{{ $assignment->title }}</h1>
                        <div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-day mr-1"></i>
                                Dibuat: {{ $assignment->created_at->format('d M Y') }}
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-1 {{ $assignment->deadline < now() ? 'text-red-500' : 'text-gray-400' }}"></i>
                                <span class="{{ $assignment->deadline < now() ? 'text-red-500 font-medium' : '' }}">Deadline: {{ $assignment->deadline->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-star mr-1 text-yellow-500"></i>
                                <span>Nilai Maksimal: {{ $assignment->max_score }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('guru.assignments.edit', $assignment) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors flex items-center gap-1">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('guru.assignments.destroy', $assignment) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors flex items-center gap-1">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Assignment Content -->
            <div class="prose max-w-none mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Deskripsi Tugas</h3>
                <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                    {!! nl2br(e($assignment->description)) !!}
                </div>
            </div>
            
            @if($assignment->attachment_path)
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Lampiran Tugas</h3>
                    <div class="flex items-center bg-blue-50 border border-blue-100 p-4 rounded-md">
                        <div class="p-2 bg-blue-100 text-blue-600 rounded-md mr-4">
                            <i class="fas fa-paperclip text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-700 font-medium mb-1">{{ Str::afterLast($assignment->attachment_path, '/') }}</p>
                            <p class="text-sm text-gray-500">File tugas yang harus dikerjakan</p>
                        </div>
                        <a href="{{ Storage::url($assignment->attachment_path) }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-download mr-1"></i> Download
                        </a>
                    </div>
                </div>
            @endif
            
            <!-- Assignment Stats Card -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100/50">
                    <div class="flex items-center mb-3">
                        <div class="p-2 rounded-full bg-indigo-100 text-indigo-600 mr-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4 class="font-medium text-gray-700">Total Siswa</h4>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ $assignment->classroom->students->count() ?? 0 }}</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100/50">
                    <div class="flex items-center mb-3">
                        <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h4 class="font-medium text-gray-700">Sudah Mengumpulkan</h4>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ $submissions->count() }}</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100/50">
                    <div class="flex items-center mb-3">
                        <div class="p-2 rounded-full bg-red-100 text-red-600 mr-3">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <h4 class="font-medium text-gray-700">Belum Mengumpulkan</h4>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ ($assignment->classroom->students->count() ?? 0) - $submissions->count() }}</p>
                </div>
            </div>
              <!-- Submissions -->
            <div class="border-t border-gray-200 pt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Daftar Pengumpulan</h3>
                    <a href="{{ route('guru.submissions.index', $assignment->id) }}" class="flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-all">
                        <i class="fas fa-list-check mr-2"></i> Kelola Penilaian
                    </a>
                </div>
                
                @if($submissions->count() > 0)
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-100/50">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Pengumpulan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($submissions as $submission)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-500">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $submission->student->name ?? 'Unknown' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                {{ $submission->submitted_at->format('d M Y, H:i') }}
                                                @if($submission->submitted_at->gt($assignment->deadline))
                                                    <span class="inline-flex items-center ml-1 px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="fas fa-clock mr-1"></i> Terlambat
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($submission->score !== null)
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
                                            @if($submission->score !== null)
                                                <span class="font-medium text-green-600">{{ $submission->score }}</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                @if($submission->file_path)
                                                <a href="{{ route('guru.submissions.download', ['assignment' => $assignment->id, 'submission' => $submission->id]) }}" class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 p-1.5 rounded-md transition-colors" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                @endif
                                                <a href="{{ route('guru.submissions.show', ['assignment' => $assignment->id, 'submission' => $submission->id]) }}" class="text-yellow-600 hover:text-yellow-900 bg-yellow-100 hover:bg-yellow-200 p-1.5 rounded-md transition-colors" title="{{ $submission->isGraded() ? 'Edit Nilai' : 'Beri Nilai' }}">
                                                    <i class="fas fa-{{ $submission->isGraded() ? 'edit' : 'star' }}"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>                @else
                    <div class="bg-gray-50 rounded-lg p-8 text-center">
                        <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 text-yellow-600 mb-4">
                            <i class="fas fa-exclamation-circle text-2xl"></i>
                        </div>
                        <h4 class="text-base font-medium text-gray-800 mb-1">Belum ada siswa yang mengumpulkan</h4>
                        <p class="text-gray-500 mb-4">Belum ada siswa yang mengumpulkan tugas ini.</p>
                        <p class="text-gray-500 text-sm">Ketika siswa mulai mengumpulkan tugas, Anda dapat menilai tugas mereka di sini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .prose {
        line-height: 1.75;
    }
    
    .prose p {
        margin-top: 1.25em;
        margin-bottom: 1.25em;
    }
    
    .prose strong {
        font-weight: 600;
    }
    
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