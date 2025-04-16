@extends('layouts.dashboard')

@php
    // Define $attendance as an alias for $session to fix undefined variable error
    $attendance = $session ?? null;
@endphp

@section('page-title', 'Isi Absensi')

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
                                <p class="mb-1 text-muted small">Kelas</p>
                                <h6 class="fw-bold">{{ $session->class->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1 text-muted small">Mata Pelajaran</p>
                                <h6 class="fw-bold">{{ $session->subject->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1 text-muted small">Tanggal</p>
                                <h6 class="fw-bold">{{ \Carbon\Carbon::parse($session->date)->format('d M Y') }}</h6>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1 text-muted small">Waktu</p>
                                <h6 class="fw-bold">{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('attendance.update', $session->id) }}" method="POST" id="attendanceForm">
            @csrf
            @method('PUT')
            
            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Aksi Cepat</h5>
                    <div class="btn-group mb-3">
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

            <div id="batch-action-feedback" class="alert alert-info mt-3 d-none">
                <i class="bx bx-info-circle me-1"></i>
                <span id="feedback-message"></span>
            </div>

            <div class="table-responsive mt-3">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="30%">Nama Siswa</th>
                            <th width="15%">NIS</th>
                            <th>Status Kehadiran</th>
                            <th width="20%">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $index => $student)
                        <tr class="attendance-card">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->nis }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <input type="radio" class="btn-check" name="status[{{ $student->id }}]" id="hadir-{{ $student->id }}" value="hadir" {{ (isset($attendances[$student->id]) && $attendances[$student->id]->status == 'hadir') ? 'checked' : '' }} autocomplete="off">
                                    <label class="btn btn-outline-success btn-sm" for="hadir-{{ $student->id }}">H</label>
                                    
                                    <input type="radio" class="btn-check" name="status[{{ $student->id }}]" id="izin-{{ $student->id }}" value="izin" {{ (isset($attendances[$student->id]) && $attendances[$student->id]->status == 'izin') ? 'checked' : '' }} autocomplete="off">
                                    <label class="btn btn-outline-warning btn-sm" for="izin-{{ $student->id }}">I</label>
                                    
                                    <input type="radio" class="btn-check" name="status[{{ $student->id }}]" id="sakit-{{ $student->id }}" value="sakit" {{ (isset($attendances[$student->id]) && $attendances[$student->id]->status == 'sakit') ? 'checked' : '' }} autocomplete="off">
                                    <label class="btn btn-outline-info btn-sm" for="sakit-{{ $student->id }}">S</label>
                                    
                                    <input type="radio" class="btn-check" name="status[{{ $student->id }}]" id="alpa-{{ $student->id }}" value="alpa" {{ (isset($attendances[$student->id]) && $attendances[$student->id]->status == 'alpa') ? 'checked' : '' }} autocomplete="off">
                                    <label class="btn btn-outline-danger btn-sm" for="alpa-{{ $student->id }}">A</label>
                                    
                                    <input type="radio" class="btn-check" name="status[{{ $student->id }}]" id="terlambat-{{ $student->id }}" value="terlambat" {{ (isset($attendances[$student->id]) && $attendances[$student->id]->status == 'terlambat') ? 'checked' : '' }} autocomplete="off">
                                    <label class="btn btn-outline-secondary btn-sm" for="terlambat-{{ $student->id }}">T</label>
                                </div>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="notes[{{ $student->id }}]" value="{{ isset($attendances[$student->id]) ? $attendances[$student->id]->notes : '' }}" placeholder="Catatan">
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Tidak ada siswa dalam kelas ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_completed" name="is_completed" value="1" {{ $session->is_completed ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_completed">
                        Tandai sebagai selesai
                    </label>
                    <div class="small text-muted">Absensi yang ditandai selesai tidak dapat diubah lagi.</div>
                </div>
                
                <div>
                    <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="bx bx-x me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary" id="saveButton">
                        <i class="bx bx-save me-1"></i> Simpan Absensi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .attendance-card.changed {
        border-color: var(--primary) !important;
        background-color: rgba(0, 102, 179, 0.05);
        transition: all 0.3s;
    }

    .saved-indicator {
        position: absolute;
        top: 5px;
        right: 5px;
        background: var(--success);
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        transform: scale(0);
        transition: transform 0.3s;
    }

    .saved-indicator.show {
        transform: scale(1);
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize variables
        const selectAllHadir = document.getElementById('select-all-hadir');
        const selectAllIzin = document.getElementById('select-all-izin');
        const selectAllSakit = document.getElementById('select-all-sakit');
        const selectAllAlpa = document.getElementById('select-all-alpa');
        const selectAllTerlambat = document.getElementById('select-all-terlambat');
        const clearAll = document.getElementById('clear-all');
        const batchFeedback = document.getElementById('batch-action-feedback');
        const feedbackMessage = document.getElementById('feedback-message');
        const studentSearch = document.getElementById('student-search');
        const clearSearch = document.getElementById('clear-search');
        const attendanceCards = document.querySelectorAll('.attendance-card');
        const attendanceForm = document.getElementById('attendanceForm');
        const saveButton = document.getElementById('saveButton');
        
        // Function to show batch feedback
        function showBatchFeedback(message, type = 'info') {
            if (!batchFeedback || !feedbackMessage) return;
            
            batchFeedback.className = `alert alert-${type} mt-3`;
            batchFeedback.classList.remove('d-none');
            feedbackMessage.textContent = message;
            
            // Hide the feedback after 3 seconds
            setTimeout(() => {
                batchFeedback.classList.add('d-none');
            }, 3000);
        }
        
        // Function to show toast notification
        function showToast(message, type = 'info') {
            // Implementation for toast notifications if needed
        }
        
        // Function to handle batch status changes
        function handleBatchStatus(selector, status, message, alertType) {
            const radios = document.querySelectorAll(selector);
            if (radios.length === 0) {
                showBatchFeedback('Tidak ada siswa yang tersedia', 'warning');
                return false;
            }
            
            // Confirmation dialog
            if (confirm(`Apakah Anda yakin ingin menandai semua siswa sebagai "${status.toUpperCase()}"?`)) {
                radios.forEach(radio => {
                    radio.checked = true;
                    const cardElement = radio.closest('.attendance-card');
                    if (cardElement) cardElement.classList.add('changed');
                });
                
                showBatchFeedback(message, alertType);
                showToast(message, alertType);
            }
        }
        
        // Add event listeners to batch action buttons
        if (selectAllHadir) {
            selectAllHadir.addEventListener('click', function(e) {
                e.preventDefault();
                handleBatchStatus('[id^="hadir-"]', 'hadir', 'Semua siswa ditandai hadir', 'success');
            });
        }
        
        if (selectAllIzin) {
            selectAllIzin.addEventListener('click', function(e) {
                e.preventDefault();
                handleBatchStatus('[id^="izin-"]', 'izin', 'Semua siswa ditandai izin', 'info');
            });
        }
        
        if (selectAllSakit) {
            selectAllSakit.addEventListener('click', function(e) {
                e.preventDefault();
                handleBatchStatus('[id^="sakit-"]', 'sakit', 'Semua siswa ditandai sakit', 'info');
            });
        }
        
        if (selectAllAlpa) {
            selectAllAlpa.addEventListener('click', function(e) {
                e.preventDefault();
                handleBatchStatus('[id^="alpa-"]', 'alpa', 'Semua siswa ditandai alpa', 'warning');
            });
        }
        
        if (selectAllTerlambat) {
            selectAllTerlambat.addEventListener('click', function(e) {
                e.preventDefault();
                handleBatchStatus('[id^="terlambat-"]', 'terlambat', 'Semua siswa ditandai terlambat', 'secondary');
            });
        }
        
        if (clearAll) {
            clearAll.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Anda yakin ingin mengatur ulang semua status kehadiran?')) {
                    document.querySelectorAll('.btn-check').forEach(radio => {
                        radio.checked = false;
                    });
                    
                    showBatchFeedback('Status kehadiran telah direset', 'secondary');
                    showToast('Status kehadiran direset', 'secondary');
                }
            });
        }

        // Student search functionality
        if (studentSearch) {
            studentSearch.addEventListener('input', function() {
                const searchValue = this.value.toLowerCase();
                let foundCount = 0;
                
                attendanceCards.forEach(card => {
                    const studentName = card.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const studentNis = card.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    
                    if (studentName.includes(searchValue) || studentNis.includes(searchValue)) {
                        card.style.display = '';
                        foundCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                if (searchValue && foundCount === 0) {
                    showBatchFeedback(`Tidak ada siswa yang cocok dengan "${searchValue}"`, 'warning');
                } else if (searchValue) {
                    showBatchFeedback(`Ditemukan ${foundCount} siswa yang cocok`, 'info');
                } else {
                    batchFeedback.classList.add('d-none');
                }
            });
        }
        
        if (clearSearch) {
            clearSearch.addEventListener('click', function() {
                studentSearch.value = '';
                attendanceCards.forEach(card => {
                    card.style.display = '';
                });
                batchFeedback.classList.add('d-none');
            });
        }
        
        // Form submission with animation
        if (attendanceForm) {
            attendanceForm.addEventListener('submit', function(e) {
                // Optionally add validation logic here
                
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
