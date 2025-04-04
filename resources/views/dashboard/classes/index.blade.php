@extends('layouts.dashboard')

@section('page-title', 'Data Kelas')

@section('page-actions')
<a href="{{ route('classes.create') }}" class="btn btn-sm btn-primary">
    <i class="bx bx-plus me-1"></i> Tambah Kelas
</a>
@endsection

@section('dashboard-content')
<div class="row">
    <!-- Success message -->
    @if(session('success'))
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Manajemen Kelas</h5>
                <div>
                    <select class="form-select form-select-sm" id="filterAcademicYear">
                        <option value="">Semua Tahun Ajaran</option>
                        <option value="2023/2024">2023/2024</option>
                        <option value="2024/2025">2024/2025</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="classTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="class-x-tab" data-bs-toggle="tab" data-bs-target="#class-x" type="button" role="tab" aria-controls="class-x" aria-selected="true">Kelas X</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="class-xi-tab" data-bs-toggle="tab" data-bs-target="#class-xi" type="button" role="tab" aria-controls="class-xi" aria-selected="false">Kelas XI</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="class-xii-tab" data-bs-toggle="tab" data-bs-target="#class-xii" type="button" role="tab" aria-controls="class-xii" aria-selected="false">Kelas XII</button>
                    </li>
                </ul>
                <div class="tab-content mt-3" id="classTabContent">
                    <!-- Kelas X -->
                    <div class="tab-pane fade show active" id="class-x" role="tabpanel" aria-labelledby="class-x-tab">
                        @if($classGroups['X']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama Kelas</th>
                                        <th>Wali Kelas</th>
                                        <th>Ruang</th>
                                        <th>Jumlah Siswa</th>
                                        <th>Kapasitas</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($classGroups['X'] as $class)
                                    <tr>
                                        <td><strong>{{ $class->name }}</strong></td>
                                        <td>{{ $class->teacher ? $class->teacher->name : '-' }}</td>
                                        <td>{{ $class->room ?? '-' }}</td>
                                        <td>{{ $class->students->count() }}</td>
                                        <td>{{ $class->capacity }}</td>
                                        <td>{{ $class->academic_year ?? '-' }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('classes.show', $class) }}" class="btn btn-light" title="Detail"><i class="bx bx-show"></i></a>
                                                <a href="{{ route('classes.edit', $class) }}" class="btn btn-light" title="Edit"><i class="bx bx-edit"></i></a>
                                                <button type="button" class="btn btn-light delete-btn" 
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                                        data-id="{{ $class->id }}" 
                                                        data-name="{{ $class->name }}" 
                                                        title="Hapus">
                                                    <i class="bx bx-trash text-danger"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bx bx-book-content text-secondary" style="font-size: 3.5rem; opacity: 0.5;"></i>
                            <h6 class="text-muted mt-3">Belum ada kelas untuk tingkat X</h6>
                            <p class="small text-muted">Silakan tambahkan kelas baru menggunakan tombol "Tambah Kelas"</p>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Kelas XI -->
                    <div class="tab-pane fade" id="class-xi" role="tabpanel" aria-labelledby="class-xi-tab">
                        @if($classGroups['XI']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama Kelas</th>
                                        <th>Wali Kelas</th>
                                        <th>Ruang</th>
                                        <th>Jumlah Siswa</th>
                                        <th>Kapasitas</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($classGroups['XI'] as $class)
                                    <tr>
                                        <td><strong>{{ $class->name }}</strong></td>
                                        <td>{{ $class->teacher ? $class->teacher->name : '-' }}</td>
                                        <td>{{ $class->room ?? '-' }}</td>
                                        <td>{{ $class->students->count() }}</td>
                                        <td>{{ $class->capacity }}</td>
                                        <td>{{ $class->academic_year ?? '-' }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('classes.show', $class) }}" class="btn btn-light" title="Detail"><i class="bx bx-show"></i></a>
                                                <a href="{{ route('classes.edit', $class) }}" class="btn btn-light" title="Edit"><i class="bx bx-edit"></i></a>
                                                <button type="button" class="btn btn-light delete-btn" 
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                                        data-id="{{ $class->id }}" 
                                                        data-name="{{ $class->name }}" 
                                                        title="Hapus">
                                                    <i class="bx bx-trash text-danger"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <img src="https://via.placeholder.com/150" alt="No classes" class="img-fluid mb-3" style="opacity: 0.5; max-width: 150px;">
                            <h6 class="text-muted">Belum ada kelas untuk tingkat XI</h6>
                            <p class="small text-muted">Silakan tambahkan kelas baru menggunakan tombol "Tambah Kelas"</p>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Kelas XII -->
                    <div class="tab-pane fade" id="class-xii" role="tabpanel" aria-labelledby="class-xii-tab">
                        @if($classGroups['XII']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama Kelas</th>
                                        <th>Wali Kelas</th>
                                        <th>Ruang</th>
                                        <th>Jumlah Siswa</th>
                                        <th>Kapasitas</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($classGroups['XII'] as $class)
                                    <tr>
                                        <td><strong>{{ $class->name }}</strong></td>
                                        <td>{{ $class->teacher ? $class->teacher->name : '-' }}</td>
                                        <td>{{ $class->room ?? '-' }}</td>
                                        <td>{{ $class->students->count() }}</td>
                                        <td>{{ $class->capacity }}</td>
                                        <td>{{ $class->academic_year ?? '-' }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('classes.show', $class) }}" class="btn btn-light" title="Detail"><i class="bx bx-show"></i></a>
                                                <a href="{{ route('classes.edit', $class) }}" class="btn btn-light" title="Edit"><i class="bx bx-edit"></i></a>
                                                <button type="button" class="btn btn-light delete-btn" 
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                                        data-id="{{ $class->id }}" 
                                                        data-name="{{ $class->name }}" 
                                                        title="Hapus">
                                                    <i class="bx bx-trash text-danger"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <img src="https://via.placeholder.com/150" alt="No classes" class="img-fluid mb-3" style="opacity: 0.5; max-width: 150px;">
                            <h6 class="text-muted">Belum ada kelas untuk tingkat XII</h6>
                            <p class="small text-muted">Silakan tambahkan kelas baru menggunakan tombol "Tambah Kelas"</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Hapus Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kelas <span id="className" class="fw-bold"></span>?</p>
                <p class="text-danger small">Catatan: Semua siswa yang terkait dengan kelas ini akan kehilangan data kelas mereka.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete button clicks
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                
                document.getElementById('className').textContent = name;
                document.getElementById('deleteForm').action = `/classes/${id}`;
            });
        });
        
        // Filter by academic year
        const filterSelect = document.getElementById('filterAcademicYear');
        filterSelect.addEventListener('change', function() {
            const academicYear = this.value;
            const allRows = document.querySelectorAll('tbody tr');
            
            allRows.forEach(row => {
                const yearCell = row.cells[5].textContent.trim();
                if (!academicYear || yearCell === academicYear || yearCell === '-') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection
