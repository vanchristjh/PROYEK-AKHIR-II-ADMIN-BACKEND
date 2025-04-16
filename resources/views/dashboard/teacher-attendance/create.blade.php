@extends('layouts.dashboard')

@section('page-title', 'Buat Absensi Guru Baru')

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

        <form action="{{ route('teacher-attendance.store') }}" method="POST">
            @csrf
            
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Informasi Absensi</h5>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-3">
                                    <label for="start_time" class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time', '07:00') }}" required>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-3">
                                    <label for="end_time" class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time', '16:00') }}" required>
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card bg-light border-0 mt-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Detail Tambahan</h5>
                            
                            <div class="mb-3">
                                <label for="activity_type" class="form-label">Jenis Kegiatan</label>
                                <select class="form-select @error('activity_type') is-invalid @enderror" id="activity_type" name="activity_type">
                                    <option value="" {{ old('activity_type') === '' ? 'selected' : '' }}>-- Pilih Jenis Kegiatan --</option>
                                    <option value="Kegiatan Belajar Mengajar" {{ old('activity_type') === 'Kegiatan Belajar Mengajar' ? 'selected' : '' }}>Kegiatan Belajar Mengajar</option>
                                    <option value="Rapat Guru" {{ old('activity_type') === 'Rapat Guru' ? 'selected' : '' }}>Rapat Guru</option>
                                    <option value="Upacara" {{ old('activity_type') === 'Upacara' ? 'selected' : '' }}>Upacara</option>
                                    <option value="Ekstrakulikuler" {{ old('activity_type') === 'Ekstrakulikuler' ? 'selected' : '' }}>Ekstrakulikuler</option>
                                    <option value="Lainnya" {{ old('activity_type') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('activity_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="notes" class="form-label">Catatan</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> Buat Absensi
                        </button>
                        <a href="{{ route('teacher-attendance.index') }}" class="btn btn-outline-secondary ms-2">
                            <i class="bx bx-x me-1"></i> Batal
                        </a>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Informasi</h5>
                            <div class="alert alert-info">
                                <i class="bx bx-info-circle me-1"></i>
                                Setelah membuat sesi absensi, Anda akan diarahkan ke halaman untuk mengisi kehadiran guru.
                            </div>
                            
                            <div class="alert alert-warning">
                                <i class="bx bx-bulb me-1"></i>
                                <strong>Tip:</strong> Pastikan tanggal dan waktu yang diisi benar. Data absensi tidak dapat diubah setelah ditandai sebagai selesai.
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
        // Form validation
        const form = document.querySelector('form');
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        const submitBtn = form.querySelector('button[type="submit"]');
        
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
                return;
            }
            
            // Show loading state on button
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Memproses...';
            submitBtn.disabled = true;
        });
        
        // Initialize validation on page load
        validateTimes();
    });
</script>
@endsection
