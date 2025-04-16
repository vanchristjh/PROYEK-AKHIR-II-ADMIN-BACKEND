@extends('layouts.dashboard')

@section('page-title', 'Data Siswa')

@section('page-actions')
<a href="{{ route('students.create') }}" class="btn btn-sm btn-outline-primary">
    <i class="bx bx-plus me-1"></i> Tambah Siswa
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('students.index') }}" method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Cari</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Nama, NIS, atau NISN">
                </div>
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <label for="academic_year" class="form-label">Tahun Akademik</label>
                    <select class="form-select" id="academic_year" name="academic_year">
                        <option value="">Semua Tahun</option>
                        @foreach($academicYears ?? [] as $year)
                            <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-search me-1"></i> Cari
                    </button>
                </div>
            </div>
        </form>
        
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-1"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover border">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th class="text-center" width="8%">Foto</th>
                        <th>NIS</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Tahun Akademik</th>
                        <th>Jenis Kelamin</th>
                        <th>Orang Tua</th>
                        <th class="text-center" width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $index => $student)
                    <tr>
                        <td class="text-center">{{ $index + $students->firstItem() }}</td>
                        <td class="text-center">
                            @if($student->profile_photo)
                                <img src="{{ asset('storage/'.$student->profile_photo) }}" alt="{{ $student->name }}" class="rounded-circle" width="40" height="40">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=2d4059&color=fff&size=40" alt="{{ $student->name }}" class="rounded-circle" width="40" height="40">
                            @endif
                        </td>
                        <td>{{ $student->nis ?? '-' }}</td>
                        <td>{{ $student->nisn ?? '-' }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->class->name ?? '-' }}</td>
                        <td>{{ $student->academic_year ?? '-' }}</td>
                        <td>{{ ($student->gender == 'male' || $student->gender == 'L') ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td>{{ $student->parent_name ?? '-' }}</td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Detail">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Edit">
                                    <i class="bx bx-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            Belum ada data siswa
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            {{ $students->links() }}
        </div>
    </div>
</div>

<!-- Export Options -->
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0">Ekspor Data</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-2 mb-md-0">
                <div class="d-grid">
                    <a href="{{ route('students.export.excel') }}" class="btn btn-outline-success">
                        <i class="bx bx-file-excel me-1"></i> Ekspor ke Excel
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-grid">
                    <a href="{{ route('students.export.pdf') }}" class="btn btn-outline-danger">
                        <i class="bx bx-file-pdf me-1"></i> Ekspor ke PDF
                    </a>
                </div>
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

        // Initialize select2 if available
        if (typeof $.fn.select2 !== 'undefined') {
            $('#class_id, #academic_year').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        }
    });
</script>
@endsection