@extends('layouts.dashboard')

@section('page-title', 'Tambah Jadwal Pelajaran')

@section('page-actions')
<a href="{{ route('schedules.index') }}" class="btn btn-secondary btn-sm">
    <i class="bx bx-arrow-back me-1"></i> Kembali
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('schedules.store') }}" method="POST">
            @csrf
            
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Informasi Jadwal Pelajaran</h5>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="class_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                                    <select class="form-select @error('class_id') is-invalid @enderror" id="class_id" name="class_id" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }} ({{ $class->level }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="teacher_id" class="form-label">Guru <span class="text-danger">*</span></label>
                                    <select class="form-select @error('teacher_id') is-invalid @enderror" id="teacher_id" name="teacher_id" required>
                                        <option value="">-- Pilih Guru --</option>
                                        <optgroup label="Guru Mata Pelajaran">
                                            @foreach($teachers->where('position', 'Guru')->sortBy('name') as $teacher)
                                                <option value="{{ $teacher->id }}" 
                                                    {{ old('teacher_id', $selectedTeacherId ?? '') == $teacher->id ? 'selected' : '' }}
                                                    data-subject="{{ $teacher->subject }}">
                                                    {{ $teacher->name }} {{ $teacher->subject ? '(' . $teacher->subject . ')' : '' }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="Staff dan Lainnya">
                                            @foreach($teachers->whereIn('position', ['Kepala Sekolah', 'Staff Administrasi'])->sortBy('name') as $teacher)
                                                <option value="{{ $teacher->id }}" 
                                                    {{ old('teacher_id', $selectedTeacherId ?? '') == $teacher->id ? 'selected' : '' }}
                                                    data-subject="{{ $teacher->subject }}">
                                                    {{ $teacher->name }} {{ $teacher->subject ? '(' . $teacher->subject . ')' : '' }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                    <div id="teacherInfo" class="form-text"></div>
                                    @error('teacher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="subject" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="teacherInfo" class="mt-2"></div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="day_of_week" class="form-label">Hari <span class="text-danger">*</span></label>
                                    <select class="form-select @error('day_of_week') is-invalid @enderror" id="day_of_week" name="day_of_week" required>
                                        <option value="">-- Pilih Hari --</option>
                                        <option value="monday" {{ old('day_of_week') == 'monday' ? 'selected' : '' }}>Senin</option>
                                        <option value="tuesday" {{ old('day_of_week') == 'tuesday' ? 'selected' : '' }}>Selasa</option>
                                        <option value="wednesday" {{ old('day_of_week') == 'wednesday' ? 'selected' : '' }}>Rabu</option>
                                        <option value="thursday" {{ old('day_of_week') == 'thursday' ? 'selected' : '' }}>Kamis</option>
                                        <option value="friday" {{ old('day_of_week') == 'friday' ? 'selected' : '' }}>Jumat</option>
                                        <option value="saturday" {{ old('day_of_week') == 'saturday' ? 'selected' : '' }}>Sabtu</option>
                                        <option value="sunday" {{ old('day_of_week') == 'sunday' ? 'selected' : '' }}>Minggu</option>
                                    </select>
                                    @error('day_of_week')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="start_time" class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="end_time" class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="room" class="form-label">Ruangan</label>
                                <input type="text" class="form-control @error('room') is-invalid @enderror" id="room" name="room" value="{{ old('room') }}">
                                @error('room')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                <small class="text-muted">Tambahkan informasi atau catatan tambahan tentang jadwal ini (opsional).</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Informasi Tambahan</h5>
                            
                            <div class="mb-3">
                                <label for="academic_year" class="form-label">Tahun Akademik</label>
                                <select class="form-select" id="academic_year" name="academic_year">
                                    <option value="">-- Pilih Tahun Akademik --</option>
                                    <option value="2023/2024" {{ old('academic_year') == '2023/2024' ? 'selected' : '' }}>2023/2024</option>
                                    <option value="2024/2025" {{ old('academic_year') == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="semester" class="form-label">Semester</label>
                                <select class="form-select" id="semester" name="semester">
                                    <option value="">-- Pilih Semester --</option>
                                    <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Semester 1</option>
                                    <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="enable_notification" name="enable_notification" value="1" {{ old('enable_notification') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enable_notification">Aktifkan Notifikasi</label>
                                    <div><small class="text-muted">Pengingat akan dikirim sebelum jadwal dimulai.</small></div>
                                </div>
                            </div>
                            
                            <div id="notification_options" class="mb-3 ps-4 border-start" style="display: {{ old('enable_notification') ? 'block' : 'none' }}">
                                <label for="notify_before" class="form-label">Kirim Pengingat</label>
                                <select class="form-select" id="notify_before" name="notify_before">
                                    <option value="5" {{ old('notify_before') == '5' ? 'selected' : '' }}>5 menit sebelumnya</option>
                                    <option value="10" {{ old('notify_before') == '10' ? 'selected' : '' }}>10 menit sebelumnya</option>
                                    <option value="15" {{ old('notify_before') == '15' ? 'selected' : '' }}>15 menit sebelumnya</option>
                                    <option value="30" {{ old('notify_before') == '30' ? 'selected' : '' }}>30 menit sebelumnya</option>
                                    <option value="60" {{ old('notify_before') == '60' ? 'selected' : '' }}>1 jam sebelumnya</option>
                                </select>
                                
                                <div class="mt-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="notify_email" name="notify_email" value="1" {{ old('notify_email') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="notify_email">Kirim ke Email</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="notify_push" name="notify_push" value="1" {{ old('notify_push', '1') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="notify_push">Kirim Notifikasi Push</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Simpan Jadwal
                </button>
                <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary">
                    <i class="bx bx-x me-1"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enhanced auto-fill subject based on selected teacher
        const teacherSelect = document.getElementById('teacher_id');
        const subjectInput = document.getElementById('subject');
        const teacherInfo = document.getElementById('teacherInfo');
        
        if (teacherSelect && subjectInput) {
            // Initial check for pre-selected teacher
            const initialOption = teacherSelect.options[teacherSelect.selectedIndex];
            if (initialOption && initialOption.value && initialOption.getAttribute('data-subject')) {
                const subjectFromOption = initialOption.getAttribute('data-subject');
                if (subjectFromOption && !subjectInput.value) {
                    subjectInput.value = subjectFromOption;
                }
            }
        
            teacherSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    // Try to get subject from option data attribute first (faster)
                    const subjectFromOption = selectedOption.getAttribute('data-subject');
                    
                    if (subjectFromOption) {
                        subjectInput.value = subjectFromOption;
                        // Add visual feedback
                        subjectInput.classList.add('border-success');
                        setTimeout(() => subjectInput.classList.remove('border-success'), 2000);
                        
                        if (teacherInfo) {
                            teacherInfo.innerHTML = '<i class="bx bx-check-circle text-success"></i> Mata pelajaran diisi otomatis';
                            setTimeout(() => teacherInfo.innerHTML = '', 3000);
                        }
                    } else {
                        // Fallback to AJAX if data attribute isn't available
                        const teacherId = this.value;
                        if (teacherId) {
                            // Show loading state
                            subjectInput.value = 'Memuat data...';
                            subjectInput.disabled = true;
                            
                            // Get teacher's subject via AJAX
                            fetch(`/api/teachers/${teacherId}/subject`)
                                .then(response => response.json())
                                .then(data => {
                                    subjectInput.disabled = false;
                                    if (data.subject) {
                                        subjectInput.value = data.subject;
                                        
                                        if (teacherInfo) {
                                            teacherInfo.innerHTML = '<i class="bx bx-check-circle text-success"></i> Mata pelajaran diisi dari data guru';
                                            setTimeout(() => teacherInfo.innerHTML = '', 3000);
                                        }
                                    } else {
                                        subjectInput.value = '';
                                        if (teacherInfo) {
                                            teacherInfo.innerHTML = '<i class="bx bx-info-circle text-info"></i> Guru ini belum memiliki mata pelajaran terkait';
                                        }
                                    }
                                })
                                .catch(error => {
                                    console.error('Error fetching teacher subject:', error);
                                    subjectInput.disabled = false;
                                    subjectInput.value = '';
                                    if (teacherInfo) {
                                        teacherInfo.innerHTML = '<i class="bx bx-error-circle text-danger"></i> Gagal memuat data mata pelajaran';
                                    }
                                });
                        }
                    }
                }
            });
        }

        // Toggle notification options visibility with animation
        const enableNotificationCheckbox = document.getElementById('enable_notification');
        const notificationOptions = document.getElementById('notification_options');
        
        if (enableNotificationCheckbox && notificationOptions) {
            enableNotificationCheckbox.addEventListener('change', function() {
                notificationOptions.style.display = this.checked ? 'block' : 'none';
            });
        }
        
        // Time validation
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        
        function validateTimes() {
            if (startTimeInput && endTimeInput && startTimeInput.value && endTimeInput.value) {
                if (endTimeInput.value <= startTimeInput.value) {
                    endTimeInput.setCustomValidity('Waktu selesai harus setelah waktu mulai');
                    showTimeError("Waktu selesai harus setelah waktu mulai");
                    return false;
                } else {
                    endTimeInput.setCustomValidity('');
                    hideTimeError();
                    return true;
                }
            }
            return true;
        }
        
        function showTimeError(message) {
            // Implementation for showing time error
            const errorContainer = document.getElementById('time-error') || 
                (() => {
                    const div = document.createElement('div');
                    div.id = 'time-error';
                    div.className = 'alert alert-danger mt-2';
                    endTimeInput.parentNode.appendChild(div);
                    return div;
                })();
            
            errorContainer.textContent = message;
            errorContainer.style.display = 'block';
        }
        
        function hideTimeError() {
            const errorContainer = document.getElementById('time-error');
            if (errorContainer) {
                errorContainer.style.display = 'none';
            }
        }
        
        if (startTimeInput && endTimeInput) {
            startTimeInput.addEventListener('change', validateTimes);
            endTimeInput.addEventListener('change', validateTimes);
        }
    });
</script>
@endsection
