@extends('layouts.dashboard')

@section('page-title', 'Buat Jadwal Guru')

@section('page-actions')
<a href="{{ route('teacher-schedules.index') }}" class="btn btn-secondary btn-sm">
    <i class="bx bx-arrow-back me-1"></i> Kembali
</a>
@endsection

@section('dashboard-content')
<div class="row">
    <div class="col-lg-9">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Form Jadwal Guru</h5>
            </div>
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
                    
                    <!-- Select Teacher First -->
                    <div class="mb-4">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="card-title">1. Pilih Guru</h6>
                                <p class="text-muted small mb-3">Pilih guru yang akan dijadwalkan</p>
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="teacher_id" class="form-label">Guru <span class="text-danger">*</span></label>
                                        <select class="form-select @error('teacher_id') is-invalid @enderror" id="teacher_id" name="teacher_id" required>
                                            <option value="">-- Pilih Guru --</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" 
                                                    {{ old('teacher_id', $selectedTeacherId ?? '') == $teacher->id ? 'selected' : '' }}
                                                    data-subject="{{ $teacher->subject }}">
                                                    {{ $teacher->name }} {{ $teacher->subject ? '('.$teacher->subject.')' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('teacher_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Schedule Details -->
                    <div class="mb-4">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="card-title">2. Detail Jadwal</h6>
                                <p class="text-muted small mb-3">Lengkapi informasi jadwal mengajar</p>
                                
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
                                        <label for="subject" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
                                        <small class="text-muted">Akan terisi otomatis sesuai mata pelajaran guru</small>
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
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
                                        <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time', '07:30') }}" required>
                                        @error('start_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="end_time" class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time', '09:00') }}" required>
                                        @error('end_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="room" class="form-label">Ruangan</label>
                                        <input type="text" class="form-control @error('room') is-invalid @enderror" id="room" name="room" value="{{ old('room') }}" placeholder="Contoh: Lab Komputer, Ruang 101">
                                        @error('room')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="academic_year" class="form-label">Tahun Akademik</label>
                                        <select class="form-select" id="academic_year" name="academic_year">
                                            <option value="">-- Pilih Tahun Akademik --</option>
                                            <option value="2023/2024" {{ old('academic_year') == '2023/2024' ? 'selected' : '' }}>2023/2024</option>
                                            <option value="2024/2025" {{ old('academic_year') == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Masukkan catatan atau informasi tambahan tentang jadwal ini (opsional)">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('teacher-schedules.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> Simpan Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Informasi</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small">
                    Halaman ini digunakan untuk menambahkan jadwal guru. Pastikan untuk memperhatikan jadwal yang sudah ada
                    agar tidak terjadi bentrok jadwal.
                </p>
                
                <hr>
                
                <h6 class="text-muted">Catatan Penting:</h6>
                <ul class="text-muted small">
                    <li class="mb-2">Guru tidak dapat dijadwalkan pada waktu yang sama di kelas berbeda.</li>
                    <li class="mb-2">Ruangan tidak dapat digunakan oleh lebih dari satu kelas pada waktu yang sama.</li>
                    <li class="mb-2">Kelas tidak dapat memiliki lebih dari satu jadwal pada waktu yang sama.</li>
                </ul>
                
                <hr>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('teacher-schedules.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bx bx-calendar me-1"></i> Lihat Jadwal Guru
                    </a>
                    <a href="{{ route('teacher-schedules.weekly') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bx bx-calendar-week me-1"></i> Lihat Jadwal Mingguan
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Jadwal Guru Terbaru</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @php
                    $latestSchedules = \App\Models\ClassSchedule::with(['teacher', 'class'])
                        ->whereNotNull('teacher_id')
                        ->latest()
                        ->take(5)
                        ->get();
                    @endphp
                    
                    @forelse($latestSchedules as $schedule)
                        <a href="{{ route('schedules.show', $schedule) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $schedule->subject }}</h6>
                                <small>{{ $schedule->formatted_day }}</small>
                            </div>
                            <p class="mb-1 small">{{ $schedule->teacher->name }}</p>
                            <small class="text-muted">
                                {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                                | {{ $schedule->class->name }}
                            </small>
                        </a>
                    @empty
                        <div class="p-3 text-center text-muted">
                            <i class="bx bx-calendar-x d-block fs-3 mb-2"></i>
                            Belum ada jadwal guru
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-fill subject based on selected teacher
        const teacherSelect = document.getElementById('teacher_id');
        const subjectInput = document.getElementById('subject');
        
        teacherSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption) {
                const subject = selectedOption.getAttribute('data-subject');
                if (subject) {
                    subjectInput.value = subject;
                }
            }
        });
        
        // Trigger on load if a teacher is already selected
        if (teacherSelect.value) {
            const selectedOption = teacherSelect.options[teacherSelect.selectedIndex];
            const subject = selectedOption.getAttribute('data-subject');
            if (subject) {
                subjectInput.value = subject;
            }
        }
        
        // Validate end time is after start time
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        
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
        
        // Initialize validation on page load
        validateTimes();
    });
</script>
@endsection
