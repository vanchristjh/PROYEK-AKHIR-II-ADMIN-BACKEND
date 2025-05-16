@extends('layouts.siswa')

@section('title', 'Pengumpulan Tugas')

@push('styles')
<style>
    .task-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .task-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .status-badge {
        font-size: 0.8rem;
        padding: 0.35em 0.65em;
        border-radius: 30px;
    }
    
    .status-submitted {
        background-color: #28a745;
    }
    
    .status-pending {
        background-color: #ffc107;
        color: #212529;
    }
    
    .status-late {
        background-color: #dc3545;
    }
    
    .status-graded {
        background-color: #3498db;
    }
    
    .deadline-indicator {
        font-size: 0.9rem;
    }
    
    .deadline-near {
        color: #dc3545;
        font-weight: 600;
    }
    
    .deadline-safe {
        color: #28a745;
    }
    
    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
    }
    
    .action-btn {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 3px;
        transition: all 0.2s;
    }
    
    .action-btn:hover {
        transform: scale(1.1);
    }
    
    .file-preview {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }
    
    .assignment-filters {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.03);
    }
    
    .icon-square {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
    
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .progress {
        height: 8px;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .table {
        vertical-align: middle;
    }
    
    .table thead th {
        font-weight: 600;
        background-color: #f8f9fa;
        border-top: none;
        border-bottom: 2px solid #e3e6f0;
    }
    
    .form-select, .form-control {
        border-radius: 8px;
    }
    
    .border-left-primary {
        border-left: 4px solid var(--primary-color) !important;
    }
    
    .border-left-success {
        border-left: 4px solid #28a745 !important;
    }
    
    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }
    
    .border-left-info {
        border-left: 4px solid #3498db !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="mb-4">
        <h4 class="fw-bold text-primary mb-1">Pengumpulan Tugas</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('siswa.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
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
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Tugas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAssignments ?? '10' }}</div>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Sudah Dikumpulkan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $submittedCount ?? '7' }}</div>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Belum Dikumpulkan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingCount ?? '3' }}</div>
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
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $averageScore ?? '85' }}</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 85%"></div>
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
    <div class="assignment-filters mb-4">
        <div class="row">
            <div class="col-md-3 mb-2">
                <label for="subjectFilter" class="form-label small text-muted mb-1">Mata Pelajaran</label>
                <select class="form-select form-select-sm" id="subjectFilter">
                    <option value="">Semua Mata Pelajaran</option>
                    <option value="1">Matematika</option>
                    <option value="2">Bahasa Indonesia</option>
                    <option value="3">Bahasa Inggris</option>
                    <option value="4">Fisika</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label for="statusFilter" class="form-label small text-muted mb-1">Status</label>
                <select class="form-select form-select-sm" id="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="submitted">Sudah Dikumpulkan</option>
                    <option value="pending">Belum Dikumpulkan</option>
                    <option value="late">Terlambat</option>
                    <option value="graded">Sudah Dinilai</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label for="deadlineFilter" class="form-label small text-muted mb-1">Tenggat</label>
                <select class="form-select form-select-sm" id="deadlineFilter">
                    <option value="">Semua Tenggat</option>
                    <option value="today">Hari Ini</option>
                    <option value="tomorrow">Besok</option>
                    <option value="week">Minggu Ini</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label for="searchTugas" class="form-label small text-muted mb-1">Cari</label>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" id="searchTugas" placeholder="Cari tugas...">
                    <button class="btn btn-outline-primary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Assignments Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">Daftar Tugas</h6>
            <div>
                <span class="badge bg-light text-dark me-2" id="activeFilterBadge" style="display: none;">
                    <i class="fas fa-filter"></i> Filter Aktif
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="25%">Judul Tugas</th>
                            <th width="15%">Mata Pelajaran</th>
                            <th width="15%">Tenggat Waktu</th>
                            <th width="10%">Status</th>
                            <th width="10%">Nilai</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample Data - Replace with actual data -->
                        <tr>
                            <td>1</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-square bg-primary text-white me-3">
                                        <i class="fas fa-file-word"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Analisis Novel "Laskar Pelangi"</h6>
                                        <small class="text-muted">Diunggah: 12 Mei 2023</small>
                                    </div>
                                </div>
                            </td>
                            <td>Bahasa Indonesia</td>
                            <td>
                                <span class="deadline-indicator deadline-safe">
                                    <i class="far fa-clock"></i> 20 Mei 2023
                                </span>
                            </td>
                            <td>
                                <span class="badge status-badge status-submitted">Sudah Dikumpulkan</span>
                            </td>
                            <td>
                                <span class="fw-bold">85</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary action-btn" data-bs-toggle="tooltip" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary action-btn" data-bs-toggle="tooltip" title="Edit Pengumpulan">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success action-btn" data-bs-toggle="tooltip" title="Download">
                                    <i class="fas fa-download"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-square bg-danger text-white me-3">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Soal Latihan Matematika BAB 3</h6>
                                        <small class="text-muted">Diunggah: 15 Mei 2023</small>
                                    </div>
                                </div>
                            </td>
                            <td>Matematika</td>
                            <td>
                                <span class="deadline-indicator deadline-near">
                                    <i class="far fa-clock"></i> Besok, 23:59
                                </span>
                            </td>
                            <td>
                                <span class="badge status-badge status-pending">Belum Dikumpulkan</span>
                            </td>
                            <td>
                                <span class="text-muted">-</span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">Kumpulkan</a>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-square bg-success text-white me-3">
                                        <i class="fas fa-file-excel"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Laporan Praktikum Fisika</h6>
                                        <small class="text-muted">Diunggah: 10 Mei 2023</small>
                                    </div>
                                </div>
                            </td>
                            <td>Fisika</td>
                            <td>
                                <span class="deadline-indicator deadline-safe">
                                    <i class="far fa-clock"></i> 25 Mei 2023
                                </span>
                            </td>
                            <td>
                                <span class="badge status-badge status-graded">Sudah Dinilai</span>
                            </td>
                            <td>
                                <span class="fw-bold">92</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary action-btn" data-bs-toggle="tooltip" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success action-btn" data-bs-toggle="tooltip" title="Download">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info action-btn" data-bs-toggle="tooltip" title="Lihat Komentar">
                                    <i class="fas fa-comment"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-square bg-warning text-white me-3">
                                        <i class="fas fa-file-powerpoint"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Presentasi Sejarah Indonesia</h6>
                                        <small class="text-muted">Diunggah: 5 Mei 2023</small>
                                    </div>
                                </div>
                            </td>
                            <td>Sejarah</td>
                            <td>
                                <span class="deadline-indicator">
                                    <i class="fas fa-exclamation-circle text-danger"></i> Terlewat 2 hari
                                </span>
                            </td>
                            <td>
                                <span class="badge status-badge status-late">Terlambat</span>
                            </td>
                            <td>
                                <span class="fw-bold">75</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary action-btn" data-bs-toggle="tooltip" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary action-btn" data-bs-toggle="tooltip" title="Edit Pengumpulan">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success action-btn" data-bs-toggle="tooltip" title="Download">
                                    <i class="fas fa-download"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <span class="text-muted">Menampilkan 1-4 dari 10 tugas</span>
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm justify-content-end mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    
    <!-- Upcoming Deadlines Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary">Tenggat Waktu Mendatang</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Deadline Item 1 -->
                <div class="col-lg-6 col-md-12 mb-3">
                    <div class="card h-100 task-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title mb-0">Soal Latihan Matematika BAB 3</h5>
                                <span class="badge bg-danger">Mendesak</span>
                            </div>
                            <p class="card-text text-muted mb-3">
                                <i class="fas fa-book me-1"></i> Mata Pelajaran: Matematika
                            </p>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-danger"><i class="far fa-clock"></i> Besok, 23:59</small>
                                <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">Kumpulkan</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Deadline Item 2 -->
                <div class="col-lg-6 col-md-12 mb-3">
                    <div class="card h-100 task-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title mb-0">Laporan Praktikum Fisika</h5>
                                <span class="badge bg-warning text-dark">Minggu Ini</span>
                            </div>
                            <p class="card-text text-muted mb-3">
                                <i class="fas fa-flask me-1"></i> Mata Pelajaran: Fisika
                            </p>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><i class="far fa-clock"></i> 25 Mei 2023</small>
                                <a href="#" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Kumpulkan Tugas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="assignment_title" class="form-label">Judul Tugas</label>
                        <input type="text" class="form-control" id="assignment_title" value="Soal Latihan Matematika BAB 3" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="assignment_description" class="form-label">Deskripsi Tugas</label>
                        <textarea class="form-control" id="assignment_description" rows="3" readonly>Kerjakan latihan soal matematika BAB 3 halaman 45-50.</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="submission_file" class="form-label">Unggah File <span class="text-danger">*</span></label>
                        <input class="form-control" type="file" id="submission_file" required>
                        <small class="text-muted">Format yang diperbolehkan: PDF, DOC, DOCX. Ukuran maksimal: 10MB</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="submission_notes" class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" id="submission_notes" rows="3" placeholder="Tambahkan catatan untuk guru..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary">Kumpulkan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        
        // Add filter functionality
        const filterInputs = document.querySelectorAll('#subjectFilter, #statusFilter, #deadlineFilter, #searchTugas');
        const activeFilterBadge = document.getElementById('activeFilterBadge');
        
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.value) {
                    activeFilterBadge.style.display = 'inline-block';
                } else {
                    // Check if any filter is active
                    const anyActive = Array.from(filterInputs).some(filter => filter.value);
                    activeFilterBadge.style.display = anyActive ? 'inline-block' : 'none';
                }
                
                // Here you would normally fetch filtered data from server
                console.log('Filter changed:', this.id, this.value);
            });
            
            if (input.type === 'text') {
                input.addEventListener('keyup', function(e) {
                    if (e.key === 'Enter' && this.value) {
                        activeFilterBadge.style.display = 'inline-block';
                        // Here you would search based on the input
                        console.log('Searching for:', this.value);
                    }
                });
            }
        });
        
        // Handle modal data passing for "Kumpulkan" buttons
        const submitButtons = document.querySelectorAll('a[data-bs-toggle="modal"]');
        submitButtons.forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                if (row) {
                    const title = row.querySelector('h6').textContent;
                    document.getElementById('assignment_title').value = title;
                }
            });
        });
    });
</script>
@endpush
