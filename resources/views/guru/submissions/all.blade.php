@extends('layouts.guru')

@section('title', 'Semua Pengumpulan Tugas')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="mb-4">
        <h4 class="fw-bold text-primary mb-1">Semua Pengumpulan Tugas</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pengumpulan Tugas</li>
            </ol>
        </nav>
    </div>
    
    <!-- Status Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pengumpulan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSubmissions ?? '25' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Sudah Dinilai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $gradedCount ?? '15' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Belum Dinilai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingCount ?? '10' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle fa-2x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Nilai Rata-Rata</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ number_format($averageScore ?? 85, 1) }}</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $averageScore ?? 85 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Pengumpulan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('guru.all-submissions.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <label for="assignment" class="form-label small text-muted mb-1 font-medium">Tugas</label>
                        <select class="form-select form-select-sm rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 w-full" id="assignment" name="assignment">
                            <option value="">Semua Tugas</option>
                            @foreach($assignments ?? [] as $assignment)
                                <option value="{{ $assignment->id }}" {{ request('assignment') == $assignment->id ? 'selected' : '' }}>
                                    {{ $assignment->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="classroom" class="form-label small text-muted mb-1 font-medium">Kelas</label>
                        <select class="form-select form-select-sm rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 w-full" id="classroom" name="classroom">
                            <option value="">Semua Kelas</option>
                            @foreach($classrooms ?? [] as $classroom)
                                <option value="{{ $classroom->id }}" {{ request('classroom') == $classroom->id ? 'selected' : '' }}>
                                    {{ $classroom->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="status" class="form-label small text-muted mb-1 font-medium">Status</label>
                        <select class="form-select form-select-sm rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 w-full" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="graded" {{ request('status') == 'graded' ? 'selected' : '' }}>Sudah Dinilai</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Belum Dinilai</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="search" class="form-label small text-muted mb-1 font-medium">Pencarian</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control rounded-start border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Cari siswa..." id="search" name="search" value="{{ request('search') }}">
                            <button class="btn btn-primary px-3" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('guru.all-submissions.index') }}" class="btn btn-sm btn-outline-secondary me-2">
                        <i class="fas fa-redo me-1"></i> Reset Filter
                    </a>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-filter me-1"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Submissions List -->
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengumpulan</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownMenuLink">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-file-export fa-sm fa-fw mr-2 text-gray-400"></i>Export ke Excel</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-print fa-sm fa-fw mr-2 text-gray-400"></i>Cetak Laporan</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="submissionsTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Siswa</th>
                            <th>Tugas</th>
                            <th>Kelas</th>
                            <th>Tanggal Pengumpulan</th>
                            <th>Status</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions ?? [] as $submission)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-3">
                                            <img src="{{ asset('storage/' . ($submission->student->avatar ?? 'assets/images/default-avatar.jpg')) }}" alt="Avatar" class="rounded-circle" width="40">
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $submission->student->name ?? 'Nama Siswa' }}</h6>
                                            <small class="text-muted">{{ $submission->student->id_number ?? '12345678' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $submission->assignment->title ?? 'Judul Tugas' }}</td>
                                <td>{{ $submission->student->classroom->name ?? 'X IPA 1' }}</td>
                                <td>{{ $submission->created_at->format('d M Y H:i') ?? now()->format('d M Y H:i') }}</td>
                                <td>
                                    @if($submission->score)
                                        <span class="badge bg-success">Sudah Dinilai</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Belum Dinilai</span>
                                    @endif
                                </td>
                                <td>
                                    @if($submission->score)
                                        <span class="fw-bold">{{ $submission->score }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('guru.submissions.show', ['assignment' => $submission->assignment_id, 'submission' => $submission->id]) }}" class="btn btn-sm btn-info me-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(!$submission->score)
                                            <a href="{{ route('guru.submissions.edit', ['assignment' => $submission->assignment_id, 'submission' => $submission->id]) }}" class="btn btn-sm btn-primary me-1">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-center py-4">
                                        <img src="{{ asset('assets/images/no-data.svg') }}" alt="No Data" class="img-fluid mb-3" style="max-height: 150px;">
                                        <h5>Belum Ada Pengumpulan</h5>
                                        <p class="text-muted">Belum ada siswa yang mengumpulkan tugas atau data tidak ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Menampilkan {{ $submissions->firstItem() ?? '0' }} - {{ $submissions->lastItem() ?? '0' }} dari {{ $submissions->total() ?? '0' }} pengumpulan
                </div>
                <div>
                    {{ $submissions->links() ?? '' }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-sm {
        width: 40px;
        height: 40px;
        object-fit: cover;
    }
    
    .table td, .table th {
        vertical-align: middle;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    });
</script>
@endpush
