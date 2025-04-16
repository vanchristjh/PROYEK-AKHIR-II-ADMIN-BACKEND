@extends('layouts.dashboard')

@php
    // Define $attendance as an alias for $session or $teacherAttendance to fix undefined variable error
    $attendance = $session ?? $teacherAttendance ?? null;
@endphp

@section('page-title', 'Isi Absensi Guru')

@section('page-actions')
<a href="{{ route('teacher-attendance.index') }}" class="btn btn-secondary btn-sm">
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

        <!-- Session Info -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-light border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="bx bx-info-circle text-primary me-2"></i>
                            Informasi Absensi
                        </h5>
                        <div class="row">
                            <div class="col-md-3">
                                <p class="mb-1 text-muted small">Tanggal</p>
                                <h6 class="fw-bold">{{ \Carbon\Carbon::parse($session->date)->format('d M Y') }}</h6>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1 text-muted small">Hari</p>
                                <h6 class="fw-bold">{{ \Carbon\Carbon::parse($session->date)->locale('id')->dayName }}</h6>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1 text-muted small">Waktu</p>
                                <h6 class="fw-bold">{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</h6>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1 text-muted small">Status</p>
                                <h6 class="fw-bold">
                                    @if($session->is_completed)
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-warning">Belum Selesai</span>
                                    @endif
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Teacher Attendance Form -->
        <form action="{{ route('teacher-attendance.update', $session->id) }}" method="POST" id="attendanceForm">
            @csrf
            @method('PUT')
            
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Daftar Guru</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;">No</th>
                                    <th style="width: 80px;">Foto</th>
                                    <th>Nama Guru</th>
                                    <th>NIP</th>
                                    <th style="width: 180px;">Status</th>
                                    <th style="width: 250px;">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teachers ?? [] as $index => $teacher)
                                <tr>
                                    <td class="align-middle">{{ $index + 1 }}</td>
                                    <td class="align-middle">
                                        @if($teacher->profile_photo)
                                            <img src="{{ asset('storage/'.$teacher->profile_photo) }}" alt="{{ $teacher->name }}" class="rounded-circle" width="40" height="40">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}&background=0066b3&color=fff&size=40" alt="{{ $teacher->name }}" class="rounded-circle" width="40" height="40">
                                        @endif
                                    </td>
                                    <td class="align-middle fw-medium">{{ $teacher->name }}</td>
                                    <td class="align-middle">{{ $teacher->nip ?? '-' }}</td>
                                    <td class="align-middle">
                                        <div class="btn-group w-100" role="group">
                                            <input type="hidden" name="teacher_id[]" value="{{ $teacher->id }}">
                                            <input type="radio" class="btn-check" name="status[{{ $teacher->id }}]" id="hadir-{{ $teacher->id }}" value="hadir" {{ (isset($attendances[$teacher->id]) && $attendances[$teacher->id]->status == 'hadir') ? 'checked' : '' }} autocomplete="off">
                                            <label class="btn btn-outline-success" for="hadir-{{ $teacher->id }}" data-bs-toggle="tooltip" title="Hadir">H</label>
                                            
                                            <input type="radio" class="btn-check" name="status[{{ $teacher->id }}]" id="izin-{{ $teacher->id }}" value="izin" {{ (isset($attendances[$teacher->id]) && $attendances[$teacher->id]->status == 'izin') ? 'checked' : '' }} autocomplete="off">
                                            <label class="btn btn-outline-warning" for="izin-{{ $teacher->id }}" data-bs-toggle="tooltip" title="Izin">I</label>
                                            
                                            <input type="radio" class="btn-check" name="status[{{ $teacher->id }}]" id="sakit-{{ $teacher->id }}" value="sakit" {{ (isset($attendances[$teacher->id]) && $attendances[$teacher->id]->status == 'sakit') ? 'checked' : '' }} autocomplete="off">
                                            <label class="btn btn-outline-info" for="sakit-{{ $teacher->id }}" data-bs-toggle="tooltip" title="Sakit">S</label>
                                            
                                            <input type="radio" class="btn-check" name="status[{{ $teacher->id }}]" id="alpa-{{ $teacher->id }}" value="alpa" {{ (isset($attendances[$teacher->id]) && $attendances[$teacher->id]->status == 'alpa') ? 'checked' : '' }} autocomplete="off">
                                            <label class="btn btn-outline-danger" for="alpa-{{ $teacher->id }}" data-bs-toggle="tooltip" title="Alpa">A</label>
                                            
                                            <input type="radio" class="btn-check" name="status[{{ $teacher->id }}]" id="terlambat-{{ $teacher->id }}" value="terlambat" {{ (isset($attendances[$teacher->id]) && $attendances[$teacher->id]->status == 'terlambat') ? 'checked' : '' }} autocomplete="off">
                                            <label class="btn btn-outline-secondary" for="terlambat-{{ $teacher->id }}" data-bs-toggle="tooltip" title="Terlambat">T</label>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <input type="text" class="form-control form-control-sm" name="notes[{{ $teacher->id }}]" placeholder="Catatan (opsional)" value="{{ isset($attendances[$teacher->id]) ? $attendances[$teacher->id]->notes : '' }}">
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="bx bx-info-circle fs-4 d-block mb-2"></i>
                                        Tidak ada guru yang tersedia
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Aksi Cepat</h5>
                    <div class="btn-group gap-2 mb-3">
                        <button type="button" id="select-all-hadir" class="btn btn-outline-success">
                            <i class="bx bx-check-all me-1"></i> Semua Hadir
                        </button>
                        <button type="button" id="select-all-izin" class="btn btn-outline-warning">
                            <i class="bx bx-check-all me-1"></i> Semua Izin
                        </button>
                        <button type="button" id="select-all-sakit" class="btn btn-outline-info">
                            <i class="bx bx-check-all me-1"></i> Semua Sakit
                        </button>
                        <button type="button" id="select-all-alpa" class="btn btn-outline-danger">
                            <i class="bx bx-check-all me-1"></i> Semua Alpa
                        </button>
                        <button type="button" id="select-all-terlambat" class="btn btn-outline-secondary">
                            <i class="bx bx-check-all me-1"></i> Semua Terlambat
                        </button>
                    </div>
                    <button type="button" id="clear-all" class="btn btn-outline-secondary">
                        <i class="bx bx-reset me-1"></i> Reset
                    </button>
                </div>
            </div>
            
            <div id="batch-action-feedback" class="mt-3 alert alert-info d-none">
                <i class="bx bx-info-circle me-1"></i> <span id="feedback-message"></span>
            </div>
            
            <div class="mt-4">
                <div class="d-flex justify-content-between flex-wrap gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_completed" name="is_completed" value="1" {{ $session->is_completed ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_completed">
                            Tandai sebagai selesai
                        </label>
                        <div class="small text-muted">Absensi yang ditandai selesai tidak dapat diubah lagi.</div>
                    </div>
                    
                    <div>
                        <a href="{{ route('teacher-attendance.index') }}" class="btn btn-outline-secondary me-2">
                            <i class="bx bx-x me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary" id="saveButton">
                            <i class="bx bx-save me-1"></i> Simpan Absensi
                        </button>
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
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Quick actions for attendance
        const selectAllHadir = document.getElementById('select-all-hadir');
        const selectAllIzin = document.getElementById('select-all-izin');
        const selectAllSakit = document.getElementById('select-all-sakit');
        const selectAllAlpa = document.getElementById('select-all-alpa');
        const selectAllTerlambat = document.getElementById('select-all-terlambat');
        const clearAll = document.getElementById('clear-all');
        const batchFeedback = document.getElementById('batch-action-feedback');
        const feedbackMessage = document.getElementById('feedback-message');
        
        // Function to handle batch actions
        function handleBatchAction(selector, status, message, alertClass) {
            const radios = document.querySelectorAll(selector);
            if (radios.length === 0) {
                showFeedback('Tidak ada guru yang tersedia', 'warning');
                return false;
            }
            
            radios.forEach(radio => {
                radio.checked = true;
                addVisualFeedback(radio.closest('tr'));
            });
            
            showBatchFeedback(message, alertClass);
            return true;
        }
        
        // Display feedback in the batch action section
        function showBatchFeedback(message, type = 'info') {
            if (batchFeedback && feedbackMessage) {
                feedbackMessage.textContent = message;
                
                // Remove all alert classes and add the appropriate one
                batchFeedback.classList.remove('d-none', 'alert-info', 'alert-success', 'alert-warning', 'alert-danger', 'alert-secondary');
                batchFeedback.classList.add('alert-' + type);
                
                // Auto hide after 5 seconds
                setTimeout(() => {
                    batchFeedback.classList.add('d-none');
                }, 5000);
            }
            
            // Also show toast if available
            showFeedback(message, type);
        }
        
        if (selectAllHadir) {
            selectAllHadir.addEventListener('click', function() {
                handleBatchAction('[id^="hadir-"]', 'hadir', 'Semua guru ditandai hadir', 'success');
            });
        }
        
        if (selectAllIzin) {
            selectAllIzin.addEventListener('click', function() {
                handleBatchAction('[id^="izin-"]', 'izin', 'Semua guru ditandai izin', 'info');
            });
        }
        
        if (selectAllSakit) {
            selectAllSakit.addEventListener('click', function() {
                handleBatchAction('[id^="sakit-"]', 'sakit', 'Semua guru ditandai sakit', 'info');
            });
        }
        
        if (selectAllAlpa) {
            selectAllAlpa.addEventListener('click', function() {
                handleBatchAction('[id^="alpa-"]', 'alpa', 'Semua guru ditandai alpa', 'warning');
            });
        }
        
        if (selectAllTerlambat) {
            selectAllTerlambat.addEventListener('click', function() {
                handleBatchAction('[id^="terlambat-"]', 'terlambat', 'Semua guru ditandai terlambat', 'secondary');
            });
        }
        
        if (clearAll) {
            clearAll.addEventListener('click', function() {
                const radios = document.querySelectorAll('.btn-check');
                if (radios.length === 0) {
                    showFeedback('Tidak ada data kehadiran untuk direset', 'warning');
                    return;
                }
                
                if (confirm('Apakah Anda yakin ingin mereset semua status kehadiran?')) {
                    radios.forEach(radio => {
                        radio.checked = false;
                    });
                    showBatchFeedback('Status kehadiran berhasil direset', 'secondary');
                    
                    // Remove visual indicators
                    document.querySelectorAll('.changed-row').forEach(row => {
                        row.classList.remove('bg-light', 'changed-row');
                    });
                    
                    document.querySelectorAll('.change-indicator').forEach(indicator => {
                        indicator.remove();
                    });
                }
            });
        }
        
        // Form submission with animation and validation
        const attendanceForm = document.getElementById('attendanceForm');
        const saveButton = document.getElementById('saveButton');
        
        if (attendanceForm) {
            attendanceForm.addEventListener('submit', function(e) {
                // Validate that all teachers have a status selected
                const teachersWithoutStatus = [];
                document.querySelectorAll('tbody tr').forEach(row => {
                    const teacherName = row.querySelector('td:nth-child(3)')?.textContent;
                    if (teacherName && teacherName.trim() !== '') {
                        const hasSelectedStatus = Array.from(row.querySelectorAll('.btn-check')).some(radio => radio.checked);
                        if (!hasSelectedStatus) {
                            teachersWithoutStatus.push(teacherName);
                        }
                    }
                });
                
                if (teachersWithoutStatus.length > 0) {
                    e.preventDefault();
                    const message = `Mohon pilih status kehadiran untuk guru berikut: ${teachersWithoutStatus.join(', ')}`;
                    showBatchFeedback(message, 'danger');
                    alert(message);
                    return false;
                }
                
                if (saveButton) {
                    // Change button text and add spinner
                    saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...';
                    saveButton.disabled = true;
                }
                
                // Add saving animation to the form
                attendanceForm.classList.add('saving');
                return true;
            });
        }
        
        // Highlight changed items and keyboard shortcuts
        const statusOptions = document.querySelectorAll('.btn-check');
        statusOptions.forEach(option => {
            option.addEventListener('change', function() {
                addVisualFeedback(this.closest('tr'));
            });
        });
        
        // Add visual feedback when a row is changed
        function addVisualFeedback(row) {
            if (row) {
                row.classList.add('bg-light');
                row.classList.add('changed-row');
                
                // Create or update visual indicator
                let indicator = row.querySelector('.change-indicator');
                if (!indicator) {
                    const cell = row.querySelector('td:last-child');
                    if (cell) {
                        indicator = document.createElement('span');
                        indicator.className = 'change-indicator badge bg-primary ms-2 fade-in';
                        indicator.innerHTML = '<i class="bx bx-check"></i> Diubah';
                        cell.appendChild(indicator);
                    }
                }
            }
        }
        
        // Show feedback to user
        function showFeedback(message, type = 'info') {
            if (typeof bootstrap !== 'undefined' && typeof bootstrap.Toast !== 'undefined') {
                const toastElement = document.getElementById(`${type}Toast`) || document.getElementById('infoToast');
                if (toastElement) {
                    const messageElement = document.getElementById(`${type}ToastMessage`) || document.getElementById('infoToastMessage');
                    if (messageElement) messageElement.textContent = message;
                    const toast = new bootstrap.Toast(toastElement);
                    toast.show();
                } else {
                    console.log(`[${type.toUpperCase()}]: ${message}`);
                }
            } else {
                console.log(`[${type.toUpperCase()}]: ${message}`);
            }
        }
        
        // Add keyboard shortcuts for faster attendance input
        document.addEventListener('keydown', function(e) {
            // Only activate if no input/textarea is focused
            if (document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                // Ctrl+H = All Present
                if (e.ctrlKey && e.key === 'h') {
                    e.preventDefault();
                    selectAllHadir && selectAllHadir.click();
                }
                
                // Ctrl+R = Reset All
                if (e.ctrlKey && e.key === 'r') {
                    e.preventDefault();
                    clearAll && clearAll.click();
                }
            }
        });
    });
</script>

<style>
    .saving {
        position: relative;
    }
    
    .saving::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background-image: linear-gradient(to right, transparent, var(--primary, #0066b3), transparent);
        background-size: 200% 100%;
        animation: loading 2s infinite;
        z-index: 1000;
    }
    
    .changed-row {
        transition: background-color 0.3s ease;
    }
    
    .change-indicator {
        animation: fadeIn 0.5s;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes loading {
        0% { background-position: 100% 0; }
        100% { background-position: -100% 0; }
    }
    
    /* Add responsive styles */
    @media (max-width: 768px) {
        .btn-group {
            flex-direction: column;
            width: 100%;
        }
        
        .btn-group .btn {
            border-radius: 0.25rem !important;
            margin-bottom: 0.25rem;
        }
    }
</style>
@endsection
