@extends('layouts.dashboard')

@section('title', 'Edit Penilaian')

@section('header', 'Edit Penilaian')

@section('navigation')
    <li>
        <a href="{{ route('guru.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-tachometer-alt text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.materials.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-book text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Materi Pelajaran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.assignments.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-tasks text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Tugas</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.grades.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-3 text-white">
            <i class="fas fa-star text-lg w-6"></i>
            <span class="ml-3">Penilaian</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.attendance.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-clipboard-check text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Kehadiran</span>
        </a>
    </li>
@endsection

@section('content')
    <!-- Header with animation -->
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-star text-9xl"></i>
        </div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Edit Penilaian</h2>
            <p class="text-purple-100">Ubah penilaian langsung untuk siswa.</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 transform transition hover:shadow-md">
        <div class="p-6">
            <form action="{{ route('guru.grades.update', $grade->id) }}" method="POST" class="animate-fade-in" id="gradeForm">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Student Info -->
                    <div class="form-group mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Siswa</label>
                        <div class="flex items-center p-3 border border-gray-200 rounded-md bg-gray-50">
                            <div class="h-10 w-10 flex-shrink-0 rounded-full bg-purple-100 flex items-center justify-center text-purple-500">
                                {{ substr($grade->student->name, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <p class="font-medium">{{ $grade->student->name }}</p>
                                <p class="text-sm text-gray-500">{{ $grade->student->nis ?? 'NIS tidak tersedia' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Class & Subject Info -->
                    <div class="form-group mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Info Kelas</label>
                        <div class="flex items-center p-3 border border-gray-200 rounded-md bg-gray-50">
                            <div class="flex-grow">
                                <div class="flex justify-between items-center">
                                    <span class="px-2 py-1 bg-blue-100 text-xs rounded-md text-blue-800">
                                        {{ $grade->classroom->name }}
                                    </span>
                                    <span class="px-2 py-1 bg-green-100 text-xs rounded-md text-green-800">
                                        {{ $grade->subject->name }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">
                                    <span class="font-medium">Tipe:</span> {{ $grade->type === 'assignment' ? 'Tugas' : ucfirst($grade->type) }}
                                    @if($grade->assignment)
                                    <span class="mx-1">•</span>
                                    <span class="font-medium">Tugas:</span> {{ $grade->assignment->title }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Score and Max Score -->
                    <div class="form-group mb-5">
                        <label for="score" class="block text-sm font-medium text-gray-700 mb-1">Nilai</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-star text-gray-400"></i>
                            </div>
                            <input type="number" name="score" id="score" min="0" max="{{ $grade->max_score }}" step="0.01" value="{{ old('score', $grade->score) }}" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-shadow duration-300" required>
                        </div>
                        <p class="text-gray-500 text-xs mt-1">Nilai maksimum: {{ $grade->max_score }}</p>
                        @error('score')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Academic Info -->
                    <div class="form-group mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Info Akademik</label>
                        <div class="flex items-center p-3 border border-gray-200 rounded-md bg-gray-50">
                            <div class="flex-grow">
                                <p><span class="font-medium">Semester:</span> {{ $grade->semester }}</p>
                                <p><span class="font-medium">Tahun Ajaran:</span> {{ $grade->academic_year }}</p>
                                <p><span class="font-medium">Dibuat:</span> {{ $grade->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Feedback -->
                    <div class="form-group mb-5 md:col-span-2">
                        <label for="feedback" class="block text-sm font-medium text-gray-700 mb-1">Catatan/Feedback</label>
                        <div class="mt-1 rounded-md shadow-sm">
                            <textarea name="feedback" id="feedback" rows="4" 
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-shadow duration-300">{{ old('feedback', $grade->feedback) }}</textarea>
                        </div>
                        @error('feedback')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="border-t border-gray-200 mt-8 pt-5">
                    <div class="flex justify-end">
                        <a href="{{ route('guru.grades.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-300">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                        <button type="submit" class="ml-3 px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
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
    
    .form-group:focus-within label {
        color: #8B5CF6;
    }
    
    .form-group:focus-within i {
        color: #8B5CF6;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const gradeForm = document.getElementById('gradeForm');
        
        gradeForm.addEventListener('submit', function(e) {
            let hasError = false;
              // Check if score is less than or equal to max score
            const score = parseFloat(document.getElementById('score').value);
            const maxScore = {{ $grade->max_score }};
            const scoreInput = document.getElementById('score');
            
            if (score > maxScore) {
                e.preventDefault();
                scoreInput.classList.add('border-red-500');
                const errorMsgContainer = document.querySelector('#score-error') || document.createElement('p');
                errorMsgContainer.id = 'score-error';
                errorMsgContainer.className = 'text-red-500 text-xs mt-1';
                errorMsgContainer.textContent = 'Nilai tidak boleh lebih besar dari nilai maksimal.';
                
                if (!document.querySelector('#score-error')) {
                    scoreInput.parentNode.parentNode.appendChild(errorMsgContainer);
                }
                
                hasError = true;
            } else {
                scoreInput.classList.remove('border-red-500');
                const errorMsg = document.querySelector('#score-error');
                if (errorMsg) errorMsg.remove();
            }
              // Check if all required fields are filled
            const requiredFields = document.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                const fieldId = field.id;
                const fieldLabel = document.querySelector(`label[for="${fieldId}"]`)?.textContent || 'Field';
                
                if (!field.value) {
                    e.preventDefault();
                    field.classList.add('border-red-500');
                    
                    const errorMsgContainer = document.querySelector(`#${fieldId}-error`) || document.createElement('p');
                    errorMsgContainer.id = `${fieldId}-error`;
                    errorMsgContainer.className = 'text-red-500 text-xs mt-1';
                    errorMsgContainer.textContent = `${fieldLabel} tidak boleh kosong.`;
                    
                    if (!document.querySelector(`#${fieldId}-error`)) {
                        field.parentNode.parentNode.appendChild(errorMsgContainer);
                    }
                    
                    hasError = true;
                } else {
                    field.classList.remove('border-red-500');
                    const errorMsg = document.querySelector(`#${fieldId}-error`);
                    if (errorMsg) errorMsg.remove();
                }
            });
            
            if (hasError) {
                e.preventDefault();
                
                // Show error notification at the top
                const errorNotification = document.createElement('div');
                errorNotification.className = 'bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md animate-fade-in';
                errorNotification.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">Ada kesalahan pada form. Silakan periksa kembali.</p>
                        </div>
                    </div>
                `;
                
                // Insert at the top of the form
                const form = document.getElementById('gradeForm');
                form.insertBefore(errorNotification, form.firstChild);
                
                window.scrollTo(0, 0);
            }
        });
    });
</script>
@endpush
