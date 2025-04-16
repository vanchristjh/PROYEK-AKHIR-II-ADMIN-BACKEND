@extends('layouts.dashboard')

@section('page-title', 'Edit Kategori Nilai')

@section('page-actions')
<a href="{{ route('grade-categories.index') }}" class="btn btn-secondary btn-sm">
    <i class="bx bx-arrow-back me-1"></i> Kembali
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('grade-categories.update', $gradeCategory) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $gradeCategory->name) }}" required>
                        <div class="form-text">Contoh: Tugas Harian, UTS, UAS, Praktikum, dll.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="subject_id" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select class="form-select" id="subject_id" name="subject_id" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $gradeCategory->subject_id) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="class_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select class="form-select" id="class_id" name="class_id" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id', $gradeCategory->class_id) == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="weight" class="form-label">Bobot (%) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="weight" name="weight" value="{{ old('weight', $gradeCategory->weight) }}" min="0" max="100" required>
                        <div class="form-text">Persentase bobot kategori dalam perhitungan nilai akhir.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="academic_year" class="form-label">Tahun Akademik <span class="text-danger">*</span></label>
                        <select class="form-select" id="academic_year" name="academic_year" required>
                            <option value="">-- Pilih Tahun Akademik --</option>
                            <option value="2023/2024" {{ old('academic_year', $gradeCategory->academic_year) == '2023/2024' ? 'selected' : '' }}>2023/2024</option>
                            <option value="2024/2025" {{ old('academic_year', $gradeCategory->academic_year) == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester <span class="text-danger">*</span></label>
                        <select class="form-select" id="semester" name="semester" required>
                            <option value="">-- Pilih Semester --</option>
                            <option value="1" {{ old('semester', $gradeCategory->semester) == '1' ? 'selected' : '' }}>Semester 1</option>
                            <option value="2" {{ old('semester', $gradeCategory->semester) == '2' ? 'selected' : '' }}>Semester 2</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $gradeCategory->description) }}</textarea>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-warning">
                <i class="bx bx-info-circle me-1"></i>
                <strong>Perhatian:</strong> Mengubah mata pelajaran, kelas, atau semester dapat mempengaruhi laporan nilai siswa.
            </div>
            
            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('grade-categories.show', $gradeCategory) }}" class="btn btn-outline-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Kembali ke Detail
                </a>
                <div>
                    <button type="reset" class="btn btn-outline-secondary me-2">
                        <i class="bx bx-reset me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize select2 if available
        if (typeof $.fn.select2 !== 'undefined') {
            $('#subject_id, #class_id, #academic_year, #semester').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        }
    });
</script>
@endsection
