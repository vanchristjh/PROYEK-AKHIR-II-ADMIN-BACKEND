@extends('layouts.dashboard')

@section('page-title', 'Detail Kelas')

@section('page-actions')
<div class="d-flex">
    <a href="{{ route('classes.edit', $class) }}" class="btn btn-sm btn-primary me-2">
        <i class="bx bx-edit me-1"></i> Edit Kelas
    </a>
    <a href="{{ route('classes.index') }}" class="btn btn-sm btn-secondary">
        <i class="bx bx-arrow-back me-1"></i> Kembali
    </a>
</div>
@endsection

@section('dashboard-content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-light p-3 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bx bxs-school fs-1 text-primary"></i>
                    </div>
                    <h4 class="mb-0">{{ $class->name }}</h4>
                    <p class="text-muted">{{ $class->academic_year ?? 'Tahun Ajaran Tidak Diatur' }}</p>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <h6 class="text-uppercase text-muted small">Informasi Kelas</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Tingkat</span>
                            <span class="fw-medium">{{ $class->level }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Jurusan</span>
                            <span class="fw-medium">{{ $class->type }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Ruang Kelas</span>
                            <span class="fw-medium">{{ $class->room ?? '-' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Kapasitas</span>
                            <span class="fw-medium">{{ $class->capacity }} siswa</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted">Terisi</span>
                            <span class="fw-medium">{{ $class->students->count() }} siswa</span>
                        </li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-uppercase text-muted small">Wali Kelas</h6>
                    @if($class->teacher)
                    <div class="d-flex align-items-center">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($class->teacher->name) }}&background=0066b3&color=fff&size=48" alt="{{ $class->teacher->name }}" class="rounded-circle me-3" width="48">
                        <div>
                            <h6 class="mb-0">{{ $class->teacher->name }}</h6>
                            <p class="mb-0 small text-muted">{{ $class->teacher->nip ?? 'NIP tidak tersedia' }}</p>
                        </div>
                    </div>
                    @else
                    <p class="text-muted mb-0">Belum ditentukan</p>
                    @endif
                </div>
                
                @if($class->description)
                <div class="mb-0">
                    <h6 class="text-uppercase text-muted small">Deskripsi</h6>
                    <p class="mb-0">{{ $class->description }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Siswa</h5>
                <span class="badge bg-primary">{{ $class->students->count() }} Siswa</span>
            </div>
            <div class="card-body">
                @if($class->students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($class->students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->nis ?? '-' }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>
                                    <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-light">
                                        <i class="bx bx-edit text-primary"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <img src="https://via.placeholder.com/150" alt="No students" class="img-fluid mb-3" style="opacity: 0.5; max-width: 150px;">
                    <h6 class="text-muted">Belum ada siswa di kelas ini</h6>
                    <p class="small text-muted">Anda dapat menambahkan siswa melalui menu Data Siswa</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
