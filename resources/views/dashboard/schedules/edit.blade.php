@extends('layouts.dashboard')

@section('page-title', 'Edit Jadwal Pelajaran')

@section('page-actions')
<a href="{{ route('schedules.index') }}" class="btn btn-secondary btn-sm">
    <i class="bx bx-arrow-back me-1"></i> Kembali
</a>
@endsection

@section('dashboard-content')
<!-- Heading with breadcrumbs -->
<div class="mb-4">
    <h4 class="fw-bold py-2 mb-2"><span class="text-muted fw-light">Jadwal /</span> Edit Jadwal</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('schedules.index') }}">Jadwal Pelajaran</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<div class="card shadow-sm mb-4 border-0 overflow-hidden">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0">Edit Jadwal Pelajaran</h5>
        <p class="card-subtitle text-muted">Formulir untuk mengubah data jadwal pelajaran.</p>
    </div>
    <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <div class="d-flex">
                <i class="bx bx-error-circle fs-4 me-2"></i>
                <div>
                    <h6 class="alert-heading mb-1">Terdapat kesalahan pada input Anda!</h6>
                    <ul class="ps-3 mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route('schedules.update', $schedule) }}" method="POST" id="scheduleForm">
            @csrf
            @method('PUT')
            
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border shadow-sm h-100">
                        <div class="card-header bg-light py-3">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-calendar fs-4 me-2"></i>
                                <h5 class="card-title mb-0">Informasi Jadwal Pelajaran</h5>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="class_id" class="form-label fw-semibold">Kelas <span class="text-danger">*</span></label>
                                    <select class="form-select @error('class_id') is-invalid @enderror" id="class_id" name="class_id" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id', $schedule->class_id) == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }} ({{ $class->level }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="teacher_id" class="form-label fw-semibold">Guru <span class="text-danger">*</span></label>
                                    <select class="form-select @error('teacher_id') is-invalid @enderror" id="teacher_id" name="teacher_id" required>
                                        <option value="">-- Pilih Guru --</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $schedule->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="subject" class="form-label fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject', $schedule->subject) }}" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label for="day_of_week" class="form-label fw-semibold">Hari <span class="text-danger">*</span></label>
                                    <select class="form-select @error('day_of_week') is-invalid @enderror" id="day_of_week" name="day_of_week" required>
                                        <option value="">-- Pilih Hari --</option>
                                        <option value="monday" {{ old('day_of_week', $schedule->day_of_week) == 'monday' ? 'selected' : '' }}>Senin</option>
                                        <option value="tuesday" {{ old('day_of_week', $schedule->day_of_week) == 'tuesday' ? 'selected' : '' }}>Selasa</option>
                                        <option value="wednesday" {{ old('day_of_week', $schedule->day_of_week) == 'wednesday' ? 'selected' : '' }}>Rabu</option>
                                        <option value="thursday" {{ old('day_of_week', $schedule->day_of_week) == 'thursday' ? 'selected' : '' }}>Kamis</option>
                                        <option value="friday" {{ old('day_of_week', $schedule->day_of_week) == 'friday' ? 'selected' : '' }}>Jumat</option>
                                        <option value="saturday" {{ old('day_of_week', $schedule->day_of_week) == 'saturday' ? 'selected' : '' }}>Sabtu</option>
                                        <option value="sunday" {{ old('day_of_week', $schedule->day_of_week) == 'sunday' ? 'selected' : '' }}>Minggu</option>
                                    </select>
                                    @error('day_of_week')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="start_time" class="form-label fw-semibold">Waktu Mulai <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bx bx-time"></i></span>
                                        <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time', $schedule->start_time->format('H:i')) }}" required>
                                        @error('start_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="end_time" class="form-label fw-semibold">Waktu Selesai <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bx bx-time"></i></span>
                                        <input type="time" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time', $schedule->end_time->format('H:i')) }}" required>
                                        @error('end_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="room" class="form-label fw-semibold">Ruangan</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-building"></i></span>
                                    <input type="text" class="form-control @error('room') is-invalid @enderror" id="room" name="room" value="{{ old('room', $schedule->room) }}" placeholder="Contoh: Ruang 101" list="roomSuggestions">
                                    <datalist id="roomSuggestions">
                                        <option value="Ruang 101">
                                        <option value="Ruang 102">
                                        <option value="Ruang 103">
                                        <option value="Laboratorium Komputer">
                                        <option value="Lapangan">
                                    </datalist>
                                    @error('room')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label fw-semibold">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Contoh: Materi pembelajaran mencakup bab 3-5, pertemuan dengan ulangan harian, dsb.">{{ old('description', $schedule->description) }}</textarea>
                                <small class="text-muted"><i class="bx bx-info-circle me-1"></i>Tambahkan informasi atau catatan tambahan tentang jadwal ini (opsional).</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card border shadow-sm mb-4">
                        <div class="card-header bg-light py-3">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-info-circle fs-4 me-2"></i>
                                <h5 class="card-title mb-0">Informasi Tambahan</h5>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label for="academic_year" class="form-label fw-semibold">Tahun Akademik</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-calendar-alt"></i></span>
                                    <select class="form-select" id="academic_year" name="academic_year">
                                        <option value="">-- Pilih Tahun Akademik --</option>
                                        <option value="2023/2024" {{ old('academic_year', $schedule->academic_year) == '2023/2024' ? 'selected' : '' }}>2023/2024</option>
                                        <option value="2024/2025" {{ old('academic_year', $schedule->academic_year) == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="semester" class="form-label fw-semibold">Semester</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-book-open"></i></span>
                                    <select class="form-select" id="semester" name="semester">
                                        <option value="">-- Pilih Semester --</option>
                                        <option value="1" {{ old('semester', $schedule->semester) == '1' ? 'selected' : '' }}>Semester 1</option>
                                        <option value="2" {{ old('semester', $schedule->semester) == '2' ? 'selected' : '' }}>Semester 2</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="card bg-light border mb-3">
                                <div class="card-body p-3">
                                    <h6 class="card-subtitle mb-2">Status Jadwal</h6>
                                    <div class="form-check form-switch mb-1">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="is_active">Jadwal Aktif</label>
                                    </div>
                                    <small class="text-muted d-block ms-4"><i class="bx bx-info-circle me-1"></i>Nonaktifkan jika jadwal ini sedang tidak berlaku.</small>
                                </div>
                            </div>
                            
                            <div class="card bg-light border mb-3">
                                <div class="card-body p-3">
                                    <h6 class="card-subtitle mb-2">Pengaturan Notifikasi</h6>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="enable_notification" name="enable_notification" value="1" {{ old('enable_notification', $schedule->notification_enabled) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="enable_notification">Aktifkan Notifikasi</label>
                                    </div>
                                    <small class="text-muted d-block ms-4 mb-3"><i class="bx bx-bell me-1"></i>Pengingat akan dikirim sebelum jadwal dimulai.</small>
                                    
                                    <div id="notification_options" class="ps-4 border-start border-primary" style="{{ old('enable_notification', $schedule->notification_enabled) ? '' : 'display: none;' }}">
                                        <label for="notify_before" class="form-label fw-semibold mb-2">Kirim Pengingat</label>
                                        <select class="form-select mb-3" id="notify_before" name="notify_before">
                                            <option value="5" {{ old('notify_before', $schedule->notify_minutes_before) == '5' ? 'selected' : '' }}>5 menit sebelumnya</option>
                                            <option value="10" {{ old('notify_before', $schedule->notify_minutes_before) == '10' ? 'selected' : '' }}>10 menit sebelumnya</option>
                                            <option value="15" {{ old('notify_before', $schedule->notify_minutes_before) == '15' ? 'selected' : '' }}>15 menit sebelumnya</option>
                                            <option value="30" {{ old('notify_before', $schedule->notify_minutes_before) == '30' ? 'selected' : '' }}>30 menit sebelumnya</option>
                                            <option value="60" {{ old('notify_before', $schedule->notify_minutes_before) == '60' ? 'selected' : '' }}>1 jam sebelumnya</option>
                                        </select>
                                        
                                        <label class="form-label fw-semibold mb-2">Metode Pengiriman</label>
                                        <div class="d-flex flex-column gap-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="notify_email" name="notify_email" value="1" {{ old('notify_email', $schedule->notify_by_email) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="notify_email"><i class="bx bx-envelope me-1"></i>Kirim ke Email</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="notify_push" name="notify_push" value="1" {{ old('notify_push', $schedule->notify_by_push) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="notify_push"><i class="bx bx-bell me-1"></i>Kirim Notifikasi Push</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card bg-light border mb-3">
                                <div class="card-body p-3">
                                    <h6 class="card-subtitle mb-2">Informasi Sistem</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-1"><i class="bx bx-calendar text-muted me-1"></i> <span class="text-muted">Dibuat:</span> <span class="fw-medium">{{ $schedule->created_at->format('d M Y H:i') }}</span></li>
                                        <li class="mb-1"><i class="bx bx-edit text-muted me-1"></i> <span class="text-muted">Diperbarui:</span> <span class="fw-medium">{{ $schedule->updated_at->format('d M Y H:i') }}</span></li>
                                        <li><i class="bx bx-user text-muted me-1"></i> <span class="text-muted">Dibuat oleh:</span> <span class="fw-medium">{{ $schedule->creator ? $schedule->creator->name : 'System' }}</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="bx bx-x me-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary" id="saveButton">
                    <i class="bx bx-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="bx bx-error-circle text-danger" style="font-size: 6rem;"></i>
                </div>
                <p class="text-center mb-0">Apakah Anda yakin ingin menghapus jadwal ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('schedules.destroy', $schedule) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus Jadwal</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle notification options visibility
        const enableNotificationCheckbox = document.getElementById('enable_notification');
        const notificationOptions = document.getElementById('notification_options');
        
        enableNotificationCheckbox.addEventListener('change', function() {
            notificationOptions.style.display = this.checked ? 'block' : 'none';
        });
        
        // Enhanced teacher-subject relationship
        const teacherSelect = document.getElementById('teacher_id');
        const subjectInput = document.getElementById('subject');
        
        if (teacherSelect && subjectInput) {
            teacherSelect.addEventListener('change', function() {
                const teacherId = this.value;
                if (teacherId) {
                    // Show loading indicator
                    const originalValue = subjectInput.value;
                    subjectInput.value = 'Memuat mata pelajaran...';
                    subjectInput.disabled = true;
                    
                    // Get teacher's subject via AJAX
                    fetch(`/api/teachers/${teacherId}/subject`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Gagal memuat data mata pelajaran');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Enable input
                            subjectInput.disabled = false;
                            
                            if (data.subject) {
                                subjectInput.value = data.subject;
                                // Add visual feedback
                                subjectInput.classList.add('border-success');
                                setTimeout(() => {
                                    subjectInput.classList.remove('border-success');
                                }, 2000);
                                
                                // Show success message
                                showToast('Mata pelajaran berhasil diisi otomatis', 'success');
                            } else {
                                // If no subject found for teacher, revert to original or empty
                                subjectInput.value = originalValue || '';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching teacher subject:', error);
                            // On error, revert to original value
                            subjectInput.disabled = false;
                            subjectInput.value = originalValue || '';
                            showToast('Gagal memuat mata pelajaran: ' + error.message, 'error');
                        });
                }
            });
        }
        
        // Validate end time is after start time
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        
        function validateTimes() {
            if (startTimeInput.value && endTimeInput.value) {
                if (endTimeInput.value <= startTimeInput.value) {
                    endTimeInput.setCustomValidity('Waktu selesai harus setelah waktu mulai');
                    endTimeInput.classList.add('is-invalid');
                    return false;
                } else {
                    endTimeInput.setCustomValidity('');
                    endTimeInput.classList.remove('is-invalid');
                    return true;
                }
            }
            return true;
        }
        
        startTimeInput.addEventListener('change', validateTimes);
        endTimeInput.addEventListener('change', validateTimes);
        
        // Show toast notification
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type} border-0 position-fixed bottom-0 end-0 m-3`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            const bsToast = new bootstrap.Toast(toast, {
                autohide: true,
                delay: 3000
            });
            
            bsToast.show();
            
            toast.addEventListener('hidden.bs.toast', function() {
                this.remove();
            });
        }
        
        // Form submission with animation
        const scheduleForm = document.getElementById('scheduleForm');
        const saveButton = document.getElementById('saveButton');
        
        if (scheduleForm) {
            scheduleForm.addEventListener('submit', function(e) {
                if (!validateTimes()) {
                    e.preventDefault();
                    return false;
                }
                
                // Change button text and add spinner
                if (saveButton) {
                    saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...';
                    saveButton.disabled = true;
                }
            });
        }
    });
</script>
@endsection
