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
        
        if (classSelect && subjectSelect) {
            const updateScheduleInfo = function() {
                const classId = classSelect.value;
                const subjectId = subjectSelect.value;
                
                if (classId && subjectId) {
                    // Here you can add Ajax call to get schedule info
                    // and auto-fill start_time and end_time
                    // if you have schedule data available
                }
            };
            
            classSelect.addEventListener('change', updateScheduleInfo);
            subjectSelect.addEventListener('change', updateScheduleInfo);
        }

        // Validate end time is after start time
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        const form = document.querySelector('form');
        
        function validateTimes() {
            if (startTimeInput.value && endTimeInput.value) {
                if (endTimeInput.value <= startTimeInput.value) {
                    endTimeInput.setCustomValidity('Waktu selesai harus setelah waktu mulai');
                    return false;
                } else {
                    endTimeInput.setCustomValidity('');
                    return true;
                }
            }
            return true;
        }
        
        startTimeInput.addEventListener('change', validateTimes);
        endTimeInput.addEventListener('change', validateTimes);
        
        // Validate form before submission
        form.addEventListener('submit', function(e) {
            if (!validateTimes()) {
                e.preventDefault();
                alert('Waktu selesai harus setelah waktu mulai');
            }
        });
        
        // Initialize validation on page load
        validateTimes();
    });
</script>
@endsection
