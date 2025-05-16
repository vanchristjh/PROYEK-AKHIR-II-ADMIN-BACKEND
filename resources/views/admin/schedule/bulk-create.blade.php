@extends('layouts.dashboard')

@section('title', 'Tambah Jadwal Massal')

@section('header', 'Tambah Jadwal Pelajaran Massal')

@section('navigation')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.schedule.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center text-sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Jadwal
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('errors'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p class="font-medium mb-2">Terdapat kesalahan pada jadwal:</p>
            <ul class="list-disc ml-5">
                @foreach(session('errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Create Bulk Schedule Form -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
        <div class="p-5 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Form Tambah Jadwal Massal</h3>
            <p class="text-gray-500 text-sm mt-1">Tambahkan beberapa jadwal sekaligus untuk satu kelas</p>
        </div>
        
        <form action="{{ route('admin.schedule.bulk-store') }}" method="POST" class="p-6">
            @csrf
            
            <!-- Classroom Selection -->
            <div class="mb-6">
                <label for="classroom_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Pilih Kelas <span class="text-red-500">*</span>
                </label>
                <select id="classroom_id" name="classroom_id" class="form-select rounded-lg border-gray-300 block w-full @error('classroom_id') border-red-300 @enderror">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" {{ old('classroom_id') == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>
                @error('classroom_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Instructions -->
            <div class="bg-blue-50 p-4 rounded-lg mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Petunjuk pengisian:</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Tambahkan baris sesuai kebutuhan dengan klik tombol "Tambah Baris"</li>
                                <li>Pastikan waktu tidak bentrok dengan jadwal yang sudah ada</li>
                                <li>Pastikan guru tidak mengajar di kelas berbeda pada waktu yang sama</li>
                                <li>Pastikan waktu selesai lebih lambat dari waktu mulai pada setiap jadwal</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Schedule Rows -->
            <div class="space-y-4" id="schedule-container">
                <div class="grid grid-cols-6 gap-2 bg-gray-50 p-2 text-xs font-medium text-gray-700">
                    <div>Hari</div>
                    <div>Mata Pelajaran</div>
                    <div>Guru</div>
                    <div>Mulai</div>
                    <div>Selesai</div>
                    <div>Aksi</div>
                </div>
                
                <!-- The first row template will be cloned when adding new rows -->
                <template id="row-template">
                    <div class="schedule-row grid grid-cols-6 gap-2 items-center p-2 border border-gray-200 rounded-lg">
                        <div>
                            <select name="schedules[INDEX][day]" class="form-select rounded-md border-gray-300 block w-full text-sm day-select" required>
                                <option value="">-- Pilih Hari --</option>
                                @foreach($days as $day)
                                    <option value="{{ $day }}">{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="schedules[INDEX][subject_id]" class="form-select rounded-md border-gray-300 block w-full text-sm subject-select" required>
                                <option value="">-- Pilih Mapel --</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="schedules[INDEX][teacher_id]" class="form-select rounded-md border-gray-300 block w-full text-sm teacher-select" required>
                                <option value="">-- Pilih Guru --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <input type="time" name="schedules[INDEX][start_time]" class="form-input rounded-md border-gray-300 block w-full text-sm start-time" required>
                        </div>
                        <div>
                            <input type="time" name="schedules[INDEX][end_time]" class="form-input rounded-md border-gray-300 block w-full text-sm end-time" required>
                        </div>
                        <div>
                            <button type="button" class="remove-row px-2 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200 text-xs">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </template>
                
                <!-- Initial row -->
                <div class="schedule-row grid grid-cols-6 gap-2 items-center p-2 border border-gray-200 rounded-lg">
                    <div>
                        <select name="schedules[0][day]" class="form-select rounded-md border-gray-300 block w-full text-sm day-select" required>
                            <option value="">-- Pilih Hari --</option>
                            @foreach($days as $day)
                                <option value="{{ $day }}">{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="schedules[0][subject_id]" class="form-select rounded-md border-gray-300 block w-full text-sm subject-select" required>
                            <option value="">-- Pilih Mapel --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="schedules[0][teacher_id]" class="form-select rounded-md border-gray-300 block w-full text-sm teacher-select" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <input type="time" name="schedules[0][start_time]" class="form-input rounded-md border-gray-300 block w-full text-sm start-time" required>
                    </div>
                    <div>
                        <input type="time" name="schedules[0][end_time]" class="form-input rounded-md border-gray-300 block w-full text-sm end-time" required>
                    </div>
                    <div>
                        <button type="button" class="remove-row px-2 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200 text-xs">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Add More Button -->
            <div class="mt-4">
                <button type="button" id="add-row" class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-all">
                    <i class="fas fa-plus mr-1"></i> Tambah Baris
                </button>
            </div>
            
            <!-- Submit Button -->
            <div class="mt-8 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.schedule.index') }}" class="btn-secondary text-sm px-5 py-2 rounded-lg">
                    Batal
                </a>
                <button type="submit" class="btn-primary text-sm px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">
                    <i class="fas fa-save mr-1"></i> Simpan Semua Jadwal
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('schedule-container');
        const addRowButton = document.getElementById('add-row');
        const rowTemplate = document.getElementById('row-template').content;
        let rowCount = 1;
        
        // Add new row
        addRowButton.addEventListener('click', function() {
            const newRow = rowTemplate.cloneNode(true);
            const html = newRow.querySelector('.schedule-row').outerHTML.replace(/INDEX/g, rowCount);
            container.insertAdjacentHTML('beforeend', html);
            
            // Add fade-in animation
            const addedRow = container.lastElementChild;
            addedRow.style.opacity = '0';
            addedRow.style.transform = 'translateY(10px)';
            addedRow.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            
            setTimeout(() => {
                addedRow.style.opacity = '1';
                addedRow.style.transform = 'translateY(0)';
            }, 10);
            
            rowCount++;
            
            // Reattach event handlers
            attachRemoveHandlers();
            attachTimeValidationHandlers();
            attachSubjectChangeHandlers();
        });
        
        // Handle remove row
        function attachRemoveHandlers() {
            document.querySelectorAll('.remove-row').forEach(button => {
                button.addEventListener('click', function() {
                    // Don't remove if it's the only row
                    if (document.querySelectorAll('.schedule-row').length > 1) {
                        const row = this.closest('.schedule-row');
                        
                        // Add fade-out animation
                        row.style.opacity = '0';
                        row.style.transform = 'translateY(10px)';
                        row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        
                        // Remove after animation completes
                        setTimeout(() => {
                            row.remove();
                        }, 300);
                    } else {
                        // Visual feedback for error
                        const row = this.closest('.schedule-row');
                        row.classList.add('border-red-300');
                        row.animate([
                            { transform: 'translateX(-5px)' },
                            { transform: 'translateX(5px)' },
                            { transform: 'translateX(-5px)' },
                            { transform: 'translateX(5px)' },
                            { transform: 'translateX(0)' }
                        ], {
                            duration: 300,
                            iterations: 1
                        });
                        
                        setTimeout(() => {
                            row.classList.remove('border-red-300');
                        }, 1000);
                        
                        // Show toast notification
                        const toast = document.createElement('div');
                        toast.className = 'fixed bottom-4 right-4 bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 flex items-center';
                        toast.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i> Setidaknya harus ada satu jadwal';
                        document.body.appendChild(toast);
                        
                        // Remove toast after delay
                        setTimeout(() => {
                            toast.style.opacity = '0';
                            toast.style.transition = 'opacity 0.5s ease';
                            setTimeout(() => toast.remove(), 500);
                        }, 3000);
                    }
                });
            });
        }
        
        // Handle time validation for each row
        function attachTimeValidationHandlers() {
            document.querySelectorAll('.schedule-row').forEach(row => {
                const startInput = row.querySelector('.start-time');
                const endInput = row.querySelector('.end-time');
                
                if (startInput && endInput) {
                    const validateRowTimes = () => {
                        if (startInput.value && endInput.value && startInput.value >= endInput.value) {
                            endInput.setCustomValidity('Waktu selesai harus setelah waktu mulai');
                            // Highlight the problematic row
                            row.classList.add('bg-red-50');
                            endInput.classList.add('border-red-300');
                            startInput.classList.add('border-red-300');
                            
                            // Shake animation for visual feedback
                            endInput.animate([
                                { transform: 'translateX(-3px)' },
                                { transform: 'translateX(3px)' },
                                { transform: 'translateX(-3px)' },
                                { transform: 'translateX(0)' }
                            ], {
                                duration: 200,
                                iterations: 1
                            });
                            
                            // Add error message if not exists
                            if (!row.querySelector('.time-error')) {
                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'text-red-500 text-xs mt-1 time-error flex items-center';
                                errorDiv.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i> Waktu selesai harus setelah waktu mulai';
                                endInput.parentNode.appendChild(errorDiv);
                            }
                            
                            return false;
                        } else {
                            endInput.setCustomValidity('');
                            row.classList.remove('bg-red-50');
                            endInput.classList.remove('border-red-300');
                            startInput.classList.remove('border-red-300');
                            
                            // Remove error message if exists
                            const errorDiv = row.querySelector('.time-error');
                            if (errorDiv) {
                                errorDiv.remove();
                            }
                            
                            return true;
                        }
                    };
                    
                    startInput.addEventListener('change', validateRowTimes);
                    endInput.addEventListener('change', validateRowTimes);
                    
                    // Add real-time validation on input
                    startInput.addEventListener('input', () => {
                        if (endInput.value) validateRowTimes();
                    });
                    
                    endInput.addEventListener('input', () => {
                        if (startInput.value) validateRowTimes();
                    });
                }
            });
        }
        
        // Handle subject change to filter teachers (optional enhancement)
        function attachSubjectChangeHandlers() {
            document.querySelectorAll('.subject-select').forEach(select => {
                select.addEventListener('change', function() {
                    const row = this.closest('.schedule-row');
                    const teacherSelect = row.querySelector('.teacher-select');
                    const subjectId = this.value;
                    
                    if (subjectId) {
                        // You would need a proper API endpoint for this in your Laravel app
                        // This is just a placeholder logic assuming you have such an endpoint
                        fetch(`/admin/subjects/${subjectId}/teachers`)
                            .then(response => response.json())
                            .then(data => {
                                teacherSelect.innerHTML = '<option value="">-- Pilih Guru --</option>';
                                
                                if (data && data.length > 0) {
                                    data.forEach(teacher => {
                                        const option = document.createElement('option');
                                        option.value = teacher.id;
                                        option.textContent = teacher.name;
                                        teacherSelect.appendChild(option);
                                    });
                                } else {
                                    teacherSelect.innerHTML += '<option value="" disabled>Tidak ada guru untuk mata pelajaran ini</option>';
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching teachers:', error);
                                // On error, fallback to all teachers
                                loadAllTeachers(teacherSelect);
                            });
                    } else {
                        loadAllTeachers(teacherSelect);
                    }
                });
            });
        }
        
        // Load all teachers as fallback
        function loadAllTeachers(teacherSelect) {
            teacherSelect.innerHTML = '<option value="">-- Pilih Guru --</option>';
            @foreach($teachers as $teacher)
                teacherSelect.innerHTML += `<option value="{{ $teacher->id }}">{{ $teacher->name }}</option>`;
            @endforeach
        }
        
        // Form validation before submission
        document.querySelector('form').addEventListener('submit', function(e) {
            let isValid = true;
            let emptyFields = false;
            
            // Check classroom selection first
            const classroomSelect = document.getElementById('classroom_id');
            if (!classroomSelect.value) {
                classroomSelect.classList.add('border-red-300', 'bg-red-50');
                isValid = false;
                
                // Add error message
                if (!document.querySelector('.classroom-error')) {
                    const errorDiv = document.createElement('p');
                    errorDiv.className = 'mt-1 text-sm text-red-600 classroom-error flex items-center';
                    errorDiv.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i> Silakan pilih kelas terlebih dahulu';
                    classroomSelect.parentNode.appendChild(errorDiv);
                }
                
                // Focus on select and shake it
                classroomSelect.focus();
                classroomSelect.animate([
                    { transform: 'translateX(-5px)' },
                    { transform: 'translateX(5px)' },
                    { transform: 'translateX(-5px)' },
                    { transform: 'translateX(0)' }
                ], {
                    duration: 300,
                    iterations: 1
                });
            } else {
                classroomSelect.classList.remove('border-red-300', 'bg-red-50');
                const errorDiv = document.querySelector('.classroom-error');
                if (errorDiv) errorDiv.remove();
            }
            
            // Check each row
            document.querySelectorAll('.schedule-row').forEach(row => {
                const startInput = row.querySelector('.start-time');
                const endInput = row.querySelector('.end-time');
                const daySelect = row.querySelector('.day-select');
                const subjectSelect = row.querySelector('.subject-select');
                const teacherSelect = row.querySelector('.teacher-select');
                
                // Check for empty required fields
                if (!daySelect.value || !subjectSelect.value || !teacherSelect.value || 
                    !startInput.value || !endInput.value) {
                    emptyFields = true;
                    
                    // Highlight empty fields
                    [daySelect, subjectSelect, teacherSelect, startInput, endInput].forEach(field => {
                        if (!field.value) {
                            field.classList.add('border-red-300', 'bg-red-50');
                            
                            // Shake animation for empty fields
                            field.animate([
                                { transform: 'translateX(-3px)' },
                                { transform: 'translateX(3px)' },
                                { transform: 'translateX(-3px)' },
                                { transform: 'translateX(0)' }
                            ], {
                                duration: 200,
                                iterations: 1
                            });
                        } else {
                            field.classList.remove('border-red-300', 'bg-red-50');
                        }
                    });
                } else {
                    // Remove highlights if fields are filled
                    [daySelect, subjectSelect, teacherSelect, startInput, endInput].forEach(field => {
                        field.classList.remove('border-red-300', 'bg-red-50');
                    });
                }
                
                // Check time validity
                if (startInput.value && endInput.value && startInput.value >= endInput.value) {
                    isValid = false;
                    row.classList.add('bg-red-50');
                    endInput.classList.add('border-red-300');
                    startInput.classList.add('border-red-300');
                    
                    // Add error message if not exists
                    if (!row.querySelector('.time-error')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'text-red-500 text-xs mt-1 time-error flex items-center';
                        errorDiv.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i> Waktu selesai harus setelah waktu mulai';
                        endInput.parentNode.appendChild(errorDiv);
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                
                // Show error toast
                const errorToast = document.createElement('div');
                errorToast.className = 'fixed top-4 right-4 bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 flex items-center';
                errorToast.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i> Jadwal memiliki waktu yang tidak valid';
                document.body.appendChild(errorToast);
                
                // Remove toast after delay
                setTimeout(() => {
                    errorToast.style.opacity = '0';
                    errorToast.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => errorToast.remove(), 500);
                }, 4000);
            }
            
            if (emptyFields) {
                e.preventDefault();
                
                // Show error toast
                const errorToast = document.createElement('div');
                errorToast.className = 'fixed top-4 right-4 bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 flex items-center';
                errorToast.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i> Lengkapi semua kolom yang diperlukan';
                document.body.appendChild(errorToast);
                
                // Remove toast after delay
                setTimeout(() => {
                    errorToast.style.opacity = '0';
                    errorToast.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => errorToast.remove(), 500);
                }, 4000);
            }
            
            // Show loading state if valid
            if (isValid && !emptyFields) {
                // Disable all form inputs to prevent double submission
                this.querySelectorAll('input, select, button').forEach(el => {
                    el.disabled = true;
                });
                
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
                
                // Add loading overlay
                const loadingOverlay = document.createElement('div');
                loadingOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                loadingOverlay.innerHTML = `
                    <div class="bg-white p-6 rounded-lg shadow-xl text-center">
                        <i class="fas fa-spinner fa-spin text-blue-600 text-3xl mb-3"></i>
                        <p class="text-gray-700 text-lg mb-1">Menyimpan Jadwal</p>
                        <p class="text-gray-500 text-sm">Mohon tunggu sebentar...</p>
                    </div>
                `;
                document.body.appendChild(loadingOverlay);
            }
        });
        
        // Initial attach
        attachRemoveHandlers();
        attachTimeValidationHandlers();
        attachSubjectChangeHandlers();
        
        // Add required attribute to first row fields
        document.querySelectorAll('.schedule-row:first-child select, .schedule-row:first-child input').forEach(field => {
            field.required = true;
        });
        
        // Pulse animation for the add row button
        addRowButton.classList.add('hover:animate-pulse');
    });
</script>

<style>
    /* Custom animations and styles */
    .schedule-row {
        transition: all 0.3s ease;
    }
    
    .schedule-row:hover {
        background-color: #f9fafb;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    .shake {
        animation: shake 0.3s;
    }
    
    /* Highlight effect for newly added rows */
    .schedule-row:last-child {
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }
</style>
@endpush
