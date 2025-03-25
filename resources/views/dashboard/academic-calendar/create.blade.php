@extends('layouts.dashboard')

@section('page-title', 'Tambah Agenda Kalender Akademik')

@section('page-actions')
<a href="{{ route('academic-calendar.index') }}" class="btn btn-secondary btn-sm">
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

        <form action="{{ route('academic-calendar.store') }}" method="POST">
            @csrf
            
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Informasi Agenda</h5>
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Agenda <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                                <small class="text-muted">Berikan deskripsi detil tentang agenda ini.</small>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="start_time" class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" id="start_time" name="start_time" value="{{ old('start_time', '08:00') }}" required>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', now()->format('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="end_time" class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" id="end_time" name="end_time" value="{{ old('end_time', '16:00') }}" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="location" class="form-label">Lokasi</label>
                                <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Pengaturan Agenda</h5>
                            
                            <div class="mb-3">
                                <label for="event_type" class="form-label">Jenis Agenda <span class="text-danger">*</span></label>
                                <select class="form-select" id="event_type" name="event_type" required>
                                    <option value="academic" {{ old('event_type') == 'academic' ? 'selected' : '' }}>Akademik</option>
                                    <option value="exam" {{ old('event_type') == 'exam' ? 'selected' : '' }}>Ujian</option>
                                    <option value="holiday" {{ old('event_type') == 'holiday' ? 'selected' : '' }}>Libur</option>
                                    <option value="meeting" {{ old('event_type') == 'meeting' ? 'selected' : '' }}>Rapat</option>
                                    <option value="extracurricular" {{ old('event_type') == 'extracurricular' ? 'selected' : '' }}>Ekstrakurikuler</option>
                                    <option value="other" {{ old('event_type') == 'other' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="hidden" name="is_important" value="0">
                                    <input class="form-check-input" type="checkbox" id="is_important" name="is_important" value="1" {{ old('is_important') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_important">
                                        Tandai sebagai Agenda Penting
                                    </label>
                                </div>
                                <small class="text-muted">Agenda penting akan mendapat penekanan visual khusus.</small>
                            </div>
                        </div>
                    </div>
                    
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
                                <label for="target_audience" class="form-label">Target Peserta <span class="text-danger">*</span></label>
                                <select class="form-select" id="target_audience" name="target_audience" required>
                                    <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>Semua</option>
                                    <option value="students" {{ old('target_audience') == 'students' ? 'selected' : '' }}>Siswa</option>
                                    <option value="teachers" {{ old('target_audience') == 'teachers' ? 'selected' : '' }}>Guru</option>
                                    <option value="staff" {{ old('target_audience') == 'staff' ? 'selected' : '' }}>Staf</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> Simpan Agenda
                        </button>
                        <a href="{{ route('academic-calendar.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-x me-1"></i> Batal
                        </a>
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
        // Auto-update end date when start date changes
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        
        startDateInput.addEventListener('change', function() {
            // Only update end date if it's before start date
            if (endDateInput.value < startDateInput.value) {
                endDateInput.value = startDateInput.value;
            }
        });
        
        // Set minimum date for end date based on start date
        startDateInput.addEventListener('input', function() {
            endDateInput.min = startDateInput.value;
        });
    });
</script>
@endsection
