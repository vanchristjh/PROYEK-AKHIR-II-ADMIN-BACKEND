@extends('layouts.dashboard')

@section('page-title', 'Tambah Nilai Akademik')

@section('page-actions')
<a href="{{ route('grades.index') }}" class="btn btn-sm btn-secondary">
    <i class="bx bx-arrow-back me-1"></i> Kembali
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">Form Tambah Nilai</h5>
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

        <form action="{{ route('grades.store') }}" method="POST" id="gradeForm" class="needs-validation" novalidate>
            @csrf
            
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Informasi Dasar</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="class_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                                    <select class="form-select" id="class_id" name="class_id" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Kelas harus dipilih.
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="student_id" class="form-label">Siswa <span class="text-danger">*</span></label>
                                    <select class="form-select" id="student_id" name="student_id" required>
                                        <option value="">-- Pilih Siswa --</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Siswa harus dipilih.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="subject_id" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                                    <select class="form-select" id="subject_id" name="subject_id" required>
                                        <option value="">-- Pilih Mata Pelajaran --</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Mata pelajaran harus dipilih.
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="academic_year" class="form-label">Tahun Akademik <span class="text-danger">*</span></label>
                                    <select class="form-select" id="academic_year" name="academic_year" required>
                                        <option value="">-- Pilih Tahun Akademik --</option>
                                        <option value="2023/2024" {{ old('academic_year') == '2023/2024' ? 'selected' : '' }}>2023/2024</option>
                                        <option value="2024/2025" {{ old('academic_year') == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Tahun akademik harus dipilih.
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="semester" class="form-label">Semester <span class="text-danger">*</span></label>
                                    <select class="form-select" id="semester" name="semester" required>
                                        <option value="">-- Pilih Semester --</option>
                                        <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Semester 1</option>
                                        <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Semester harus dipilih.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 mt-4">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Data Nilai</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="assignment_score" class="form-label">Nilai Tugas</label>
                                    <input type="number" class="form-control" id="assignment_score" name="assignment_score" 
                                        value="{{ old('assignment_score') }}" min="0" max="100" step="0.01">
                                    <div class="form-text">Nilai dalam rentang 0-100</div>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="mid_exam_score" class="form-label">Nilai UTS</label>
                                    <input type="number" class="form-control" id="mid_exam_score" name="mid_exam_score" 
                                        value="{{ old('mid_exam_score') }}" min="0" max="100" step="0.01">
                                    <div class="form-text">Nilai dalam rentang 0-100</div>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="final_exam_score" class="form-label">Nilai UAS</label>
                                    <input type="number" class="form-control" id="final_exam_score" name="final_exam_score" 
                                        value="{{ old('final_exam_score') }}" min="0" max="100" step="0.01">
                                    <div class="form-text">Nilai dalam rentang 0-100</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Simpan Nilai
                </button>
                <a href="{{ route('grades.index') }}" class="btn btn-outline-secondary">
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
        const classSelect = document.getElementById('class_id');
        const studentSelect = document.getElementById('student_id');
        
        // When class is selected, load students for that class
        classSelect.addEventListener('change', function() {
            const classId = this.value;
            
            if (!classId) {
                studentSelect.innerHTML = '<option value="">-- Pilih Siswa --</option>';
                return;
            }
            
            // Show loading
            studentSelect.innerHTML = '<option value="">Memuat data siswa...</option>';
            
            // Fetch students for this class
            fetch(`/api/classes/${classId}/students`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal memuat data siswa');
                    }
                    return response.json();
                })
                .then(data => {
                    studentSelect.innerHTML = '<option value="">-- Pilih Siswa --</option>';
                    
                    data.forEach(student => {
                        const option = document.createElement('option');
                        option.value = student.id;
                        option.textContent = student.name;
                        studentSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching students:', error);
                    studentSelect.innerHTML = '<option value="">Error: Gagal memuat data siswa</option>';
                });
        });
        
        // Form validation
        const form = document.getElementById('gradeForm');
        
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
</script>
@endsection
