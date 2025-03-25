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
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-light p-3 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px;">
                        <i class="bx bxs-school fs-1 text-primary"></i>
                    </div>
                    <h4 class="mb-0 fw-bold">{{ $class->name }}</h4>
                    <p class="text-muted">{{ $class->academic_year ?? 'Tahun Ajaran Tidak Diatur' }}</p>
                    
                    <!-- Student capacity indicator -->
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-1 small">
                            <span>Kapasitas Kelas</span>
                            <span>{{ $class->students->count() }}/{{ $class->capacity }}</span>
                        </div>
                        <div class="progress" style="height: 8px; border-radius: 4px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                style="width: {{ ($class->students->count() / $class->capacity) * 100 }}%" 
                                aria-valuenow="{{ $class->students->count() }}" 
                                aria-valuemin="0" 
                                aria-valuemax="{{ $class->capacity }}"></div>
                        </div>
                    </div>
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
                    <div class="d-flex align-items-center p-3 rounded-3 bg-light">
                        @if($class->teacher->profile_photo)
                            <img src="{{ asset('storage/'.$class->teacher->profile_photo) }}" alt="{{ $class->teacher->name }}" class="rounded-circle me-3" width="48" height="48">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($class->teacher->name) }}&background=0066b3&color=fff&size=48" alt="{{ $class->teacher->name }}" class="rounded-circle me-3" width="48">
                        @endif
                        <div>
                            <h6 class="mb-0 fw-semibold">{{ $class->teacher->name }}</h6>
                            <p class="mb-0 small text-muted">{{ $class->teacher->nip ?? 'NIP tidak tersedia' }}</p>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning mb-0">
                        <i class="bx bx-info-circle me-1"></i> Belum ada wali kelas yang ditentukan
                    </div>
                    @endif
                </div>
                
                @if($class->description)
                <div class="mb-0">
                    <h6 class="text-uppercase text-muted small">Deskripsi</h6>
                    <div class="p-3 rounded-3 bg-light">
                        <p class="mb-0">{{ $class->description }}</p>
                    </div>
                </div>
                @endif
                
                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('classes.edit', $class) }}" class="btn btn-primary">
                        <i class="bx bx-edit me-1"></i> Edit Kelas
                    </a>
                    <a href="{{ route('schedules.index') }}?class_id={{ $class->id }}" class="btn btn-outline-primary">
                        <i class="bx bx-calendar me-1"></i> Lihat Jadwal Kelas
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">Daftar Siswa</h5>
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <input type="text" class="form-control form-control-sm" id="studentSearch" placeholder="Cari siswa..." style="min-width: 200px;">
                    </div>
                    <span class="badge bg-primary rounded-pill">{{ $class->students->count() }} Siswa</span>
                </div>
            </div>
            <div class="card-body">
                @if($class->students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="studentTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th style="width: 70px;">Foto</th>
                                <th>Nama Lengkap</th>
                                <th>NIS</th>
                                <th>NISN</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($class->students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($student->profile_photo)
                                        <img src="{{ asset('storage/'.$student->profile_photo) }}" alt="{{ $student->name }}" class="rounded-circle" width="40" height="40">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=0066b3&color=fff&size=40" alt="{{ $student->name }}" class="rounded-circle" width="40" height="40">
                                    @endif
                                </td>
                                <td class="fw-medium">{{ $student->name }}</td>
                                <td>{{ $student->nis ?? '-' }}</td>
                                <td>{{ $student->nisn ?? '-' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-light" data-bs-toggle="tooltip" title="Lihat Detail">
                                            <i class="bx bx-show text-primary"></i>
                                        </a>
                                        <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-light" data-bs-toggle="tooltip" title="Edit Data">
                                            <i class="bx bx-edit text-secondary"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <img src="{{ asset('images/no-students.svg') }}" alt="No students" class="img-fluid mb-3" style="opacity: 0.5; max-width: 150px;">
                    <h6 class="text-muted">Belum ada siswa di kelas ini</h6>
                    <p class="small text-muted mb-4">Anda dapat menambahkan siswa melalui menu Data Siswa</p>
                    <a href="{{ route('students.create') }}?class_id={{ $class->id }}" class="btn btn-primary">
                        <i class="bx bx-user-plus me-1"></i> Tambah Siswa Baru
                    </a>
                </div>
                @endif
            </div>
        </div>
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
        
        // Simple student search functionality
        const searchInput = document.getElementById('studentSearch');
        const table = document.getElementById('studentTable');
        
        if (searchInput && table) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = table.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }
    });
</script>
@endsection
