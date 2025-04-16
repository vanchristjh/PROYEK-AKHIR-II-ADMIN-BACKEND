@extends('layouts.dashboard')

@section('page-title', 'Nilai Akademik')

@section('page-actions')
<a href="{{ route('grades.create') }}" class="btn btn-primary btn-sm">
    <i class="bx bx-plus me-1"></i> Tambah Nilai
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title mb-3">Filter Data Nilai</h5>
        <form action="{{ route('grades.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="class_id" class="form-label">Kelas</label>
                <select class="form-select" id="class_id" name="class_id">
                    <option value="">Semua Kelas</option>
                    @foreach($classes ?? [] as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="subject_id" class="form-label">Mata Pelajaran</label>
                <select class="form-select" id="subject_id" name="subject_id">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($subjects ?? [] as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="semester" class="form-label">Semester</label>
                <select class="form-select" id="semester" name="semester">
                    <option value="">Semua Semester</option>
                    <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>Semester 1</option>
                    <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-filter me-1"></i> Filter
                </button>
                <a href="{{ route('grades.index') }}" class="btn btn-outline-secondary">
                    <i class="bx bx-reset me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Data Nilai Akademik</h5>
        @if($selectedClass)
            <span class="badge bg-primary">{{ $selectedClass->name }}</span>
        @endif
    </div>
    <div class="card-body">
        @if($students->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Mata Pelajaran</th>
                            <th>Nilai Tugas</th>
                            <th>Nilai UTS</th>
                            <th>Nilai UAS</th>
                            <th>Nilai Akhir</th>
                            <th>Grade</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                <i class="bx bx-info-circle me-2"></i> Silahkan pilih kelas untuk melihat data nilai
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4 text-muted">
                <i class="bx bx-book-content fs-1 mb-3 d-block"></i>
                <h6>Tidak ada data nilai</h6>
                <p>Pilih kelas untuk melihat data nilai siswa atau tambahkan nilai baru.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const classSelect = document.getElementById('class_id');
        
        if (classSelect) {
            classSelect.addEventListener('change', function() {
                if (this.value) {
                    document.querySelector('form').submit();
                }
            });
        }
    });
</script>
@endsection
