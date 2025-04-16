@extends('layouts.dashboard')

@section('page-title', 'Buat Absensi Baru')

@section('page-actions')
<a href="{{ route('attendance.index') }}" class="btn btn-secondary btn-sm">
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

        <form action="{{ route('attendance.store') }}" method="POST">
            @csrf
            
            <div class="row mb-4">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Informasi Absensi</h5>
                            
                            <div class="mb-3">
                                <label for="class_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                                <select class="form-select" id="class_id" name="class_id" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($classes ?? [] as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="subject_id" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                                <select class="form-select" id="subject_id" name="subject_id" required>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @foreach($subjects ?? [] as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="start_time" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" id="start_time" name="start_time" value="{{ old('start_time', '07:00') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="end_time" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" id="end_time" name="end_time" value="{{ old('end_time', '08:30') }}" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="notes" class="form-label">Catatan</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                <small class="text-muted">Catatan tambahan mengenai sesi pembelajaran ini (opsional)</small>
                            </div>

                            <div id="preview-area" class="d-none">
                                <div class="card border mt-3">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="bx bx-info-circle text-primary me-1"></i>
                                            Informasi Siswa
                                        </h6>
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span>Total siswa dalam kelas:</span>
                                            <span class="badge bg-primary" id="students-count">0</span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar" id="students-progress" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <div class="form-text mt-2 text-center" id="preview-message">
                                            Pilih kelas untuk melihat informasi siswa
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> Buat Sesi Absensi
                        </button>
                        <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-x me-1"></i> Batal
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Panduan Pengisian Absensi</h5>
                            
                            <div class="alert alert-info">
                                <h6><i class="bx bx-info-circle me-1"></i> Cara Menggunakan Fitur Absensi</h6>
                                <ol class="mb-0">
                                    <li>Pilih kelas dan mata pelajaran yang akan diabsen</li>
                                    <li>Isi tanggal dan waktu pembelajaran</li>
                                    <li>Klik "Buat Sesi Absensi" untuk membuat sesi baru</li>
                                    <li>Pada halaman berikutnya, isi status kehadiran masing-masing siswa</li>
                                    <li>Klik "Simpan Absensi" untuk menyimpan data</li>
                                </ol>
                            </div>
                            
                            <div id="feedback-area" class="alert alert-success mt-3 mb-3 d-none">
                                <i class="bx bx-check-circle me-1"></i>
                                <span id="feedback-message">Data siap diproses</span>
                            </div>
                            
                            <div class="mt-4">
                                <h6 class="mb-3">Keterangan Status Kehadiran:</h6>
                                <div class="d-flex flex-column gap-2">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-success me-2">H</span>
                                        <span>Hadir - Siswa hadir di kelas</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-warning text-dark me-2">I</span>
                                        <span>Izin - Siswa tidak hadir dengan izin</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-info me-2">S</span>
                                        <span>Sakit - Siswa tidak hadir karena sakit</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-danger me-2">A</span>
                                        <span>Alpa - Siswa tidak hadir tanpa keterangan</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-secondary me-2">T</span>
                                        <span>Terlambat - Siswa hadir terlambat</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get schedule details when class and subject are selected
        const classSelect = document.getElementById('class_id');
        const subjectSelect = document.getElementById('subject_id');
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        const form = document.querySelector('form');
        const submitBtn = document.querySelector('button[type="submit"]');
        
        if (classSelect && subjectSelect) {
            classSelect.addEventListener('change', function() {
                const classId = this.value;
                
                if (classId) {
                    // Reset subject dropdown
                    subjectSelect.innerHTML = '<option value="">Memuat mata pelajaran...</option>';
                    subjectSelect.disabled = true;
                    
                    // Make AJAX request to get subjects for this class
                    fetch(`/api/classes/${classId}/subjects`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.status === 404 
                                    ? 'Kelas tidak ditemukan' 
                                    : 'Server tidak dapat memproses permintaan');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Reset dropdown
                            subjectSelect.innerHTML = '<option value="">-- Pilih Mata Pelajaran --</option>';
                            
                            if (!data || data.length === 0) {
                                subjectSelect.innerHTML = '<option value="">Tidak ada mata pelajaran</option>';
                                showToast('Tidak ada mata pelajaran untuk kelas ini', 'warning');
                                return;
                            }
                            
                            // Add options
                            data.forEach(subject => {
                                const option = document.createElement('option');
                                option.value = subject.id;
                                option.textContent = subject.name;
                                subjectSelect.appendChild(option);
                            });
                            
                            // Re-enable select
                            subjectSelect.disabled = false;
                            
                            // If there was a previously selected subject, try to reselect it
                            const previouslySelected = "{{ old('subject_id') }}";
                            if (previouslySelected) {
                                const option = subjectSelect.querySelector(`option[value="${previouslySelected}"]`);
                                if (option) {
                                    option.selected = true;
                                    
                                    // Trigger change event to check for schedules
                                    const event = new Event('change');
                                    subjectSelect.dispatchEvent(event);
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error loading subjects:', error);
                            subjectSelect.innerHTML = '<option value="">Error memuat data</option>';
                            subjectSelect.disabled = false;
                            showToast(`Gagal memuat data mata pelajaran: ${error.message || 'Koneksi terputus'}`, 'error');
                            
                            // Add retry button
                            const retryBtn = document.createElement('button');
                            retryBtn.type = 'button';
                            retryBtn.className = 'btn btn-sm btn-outline-primary mt-2';
                            retryBtn.innerHTML = '<i class="bx bx-refresh me-1"></i> Coba Lagi';
                            retryBtn.addEventListener('click', () => {
                                const event = new Event('change');
                                classSelect.dispatchEvent(event);
                            });
                            subjectSelect.parentElement.appendChild(retryBtn);
                        });
                } else {
                    // Reset subject dropdown
                    subjectSelect.innerHTML = '<option value="">-- Pilih Mata Pelajaran --</option>';
                }
            });
            
            // Check for scheduled times when both class and subject are selected
            const updateScheduleInfo = function() {
                const classId = classSelect.value;
                const subjectId = subjectSelect.value;
                
                if (classId && subjectId) {
                    // Show loading indicator
                    const infoElement = document.createElement('div');
                    infoElement.id = 'schedule-info';
                    infoElement.className = 'alert alert-info mt-3 fade show';
                    infoElement.innerHTML = `
                        <div class="d-flex align-items-center">
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            <span>Memeriksa jadwal yang tersedia...</span>
                        </div>
                    `;
                    
                    // Replace or append the info element
                    const existingInfo = document.getElementById('schedule-info');
                    if (existingInfo) {
                        existingInfo.replaceWith(infoElement);
                    } else {
                        subjectSelect.parentElement.appendChild(infoElement);
                    }
                    
                    // Make AJAX request to get schedule info
                    fetch(`/api/schedules/check?class_id=${classId}&subject_id=${subjectId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Gagal memeriksa jadwal');
                            }
                            return response.json();
                        })
                        .then(data => {
                            const existingInfo = document.getElementById('schedule-info');
                            if (existingInfo) existingInfo.remove();
                            
                            if (data.schedule) {
                                // Show schedule info
                                const infoElement = document.createElement('div');
                                infoElement.id = 'schedule-info';
                                infoElement.className = 'alert alert-info mt-3';
                                infoElement.innerHTML = `
                                    <i class="bx bx-calendar me-2"></i>
                                    <strong>Jadwal ditemukan:</strong> ${data.schedule.day}, 
                                    ${data.schedule.start_time} - ${data.schedule.end_time}
                                    <button type="button" class="btn btn-sm btn-primary ms-2" id="use-schedule">
                                        Gunakan Waktu Ini
                                    </button>
                                `;
                                subjectSelect.parentElement.appendChild(infoElement);
                                
                                // Add click handler to use this schedule
                                document.getElementById('use-schedule').addEventListener('click', function() {
                                    startTimeInput.value = data.schedule.start_time;
                                    endTimeInput.value = data.schedule.end_time;
                                    validateTimes();
                                    showToast('Waktu jadwal diterapkan', 'success');
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error checking schedules:', error);
                            const existingInfo = document.getElementById('schedule-info');
                            if (existingInfo) existingInfo.remove();
                            
                            const errorElement = document.createElement('div');
                            errorElement.id = 'schedule-info';
                            errorElement.className = 'alert alert-warning mt-3';
                            errorElement.innerHTML = `
                                <i class="bx bx-error-circle me-2"></i>
                                <span>Gagal memeriksa jadwal: ${error.message || 'Terjadi kesalahan koneksi'}</span>
                                <button type="button" class="btn btn-sm btn-outline-primary ms-2" id="retry-schedule">
                                    <i class="bx bx-refresh me-1"></i> Coba Lagi
                                </button>
                            `;
                            subjectSelect.parentElement.appendChild(errorElement);
                            
                            document.getElementById('retry-schedule').addEventListener('click', updateScheduleInfo);
                        });
                }
            };
            
            subjectSelect.addEventListener('change', updateScheduleInfo);
        }

        function validateTimes() {
            if (startTimeInput.value && endTimeInput.value) {
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
            let errorDiv = document.getElementById('time-error');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.id = 'time-error';
                errorDiv.className = 'alert alert-danger mt-2';
                endTimeInput.parentElement.appendChild(errorDiv);
            }
            errorDiv.textContent = message;
        }
        
        function hideTimeError() {
            const errorDiv = document.getElementById('time-error');
            if (errorDiv) {
                errorDiv.remove();
            }
        }
        
        startTimeInput.addEventListener('change', validateTimes);
        endTimeInput.addEventListener('change', validateTimes);
        
        // Validate form before submission
        form.addEventListener('submit', function(e) {
            if (!validateTimes()) {
                e.preventDefault();
                return;
            }
            
            if (submitBtn) {
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Memproses...';
                submitBtn.disabled = true;
            }
        });
        
        // Initialize validation on page load
        validateTimes();
        
        // Initialize class dropdown if it has a selected value
        if (classSelect && classSelect.value) {
            const event = new Event('change');
            classSelect.dispatchEvent(event);
        }
        
        // Helper function to show toast notifications
        function showToast(message, type = 'info') {
            if (typeof bootstrap !== 'undefined' && typeof bootstrap.Toast !== 'undefined') {
                const toastElement = document.getElementById(`${type}Toast`) || document.getElementById('infoToast');
                if (toastElement) {
                    const messageElement = document.getElementById(`${type}ToastMessage`) || document.getElementById('infoToastMessage');
                    if (messageElement) messageElement.textContent = message;
                    const toast = new bootstrap.Toast(toastElement);
                    toast.show();
                } else {
                    // Fallback if toast element not found
                    alert(message);
                }
            } else {
                // Fallback if bootstrap is not loaded
                alert(message);
                console.log(`[${type.toUpperCase()}]: ${message}`);
            }
        }
    });
</script>
@endsection
