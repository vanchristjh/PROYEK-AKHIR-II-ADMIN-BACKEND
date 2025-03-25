@extends('layouts.dashboard')

@section('page-title', 'Data Mata Pelajaran')

@section('page-actions')
<a href="{{ route('subjects.create') }}" class="btn btn-primary btn-sm">
    <i class="bx bx-plus me-1"></i> Tambah Mata Pelajaran
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm mb-4 border-0 overflow-hidden">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-light border-0 filter-card">
                    <div class="card-body">
                        <h6 class="card-title mb-3">
                            <i class="bx bx-filter-alt me-1 text-primary"></i> Filter Mata Pelajaran
                        </h6>
                        
                        <div class="collapse show" id="filterCollapse">
                            <form action="{{ route('subjects.index') }}" method="GET" class="row g-3">
                                <div class="col-md-4">
                                    <label for="name" class="form-label">
                                        <i class="bx bx-book me-1 text-primary"></i> Nama Mata Pelajaran
                                    </label>
                                    <input type="text" class="form-control custom-input shadow-none" id="name" name="name" value="{{ request('name') }}" placeholder="Nama mata pelajaran...">
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="class_level" class="form-label">
                                        <i class="bx bx-line-chart me-1 text-primary"></i> Tingkat Kelas
                                    </label>
                                    <select class="form-select shadow-none" id="class_level" name="class_level">
                                        <option value="">Semua Tingkat</option>
                                        <option value="X" {{ request('class_level') == 'X' ? 'selected' : '' }}>Kelas X</option>
                                        <option value="XI" {{ request('class_level') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                                        <option value="XII" {{ request('class_level') == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="semester" class="form-label">
                                        <i class="bx bx-calendar me-1 text-primary"></i> Semester
                                    </label>
                                    <select class="form-select shadow-none" id="semester" name="semester">
                                        <option value="">Semua Semester</option>
                                        <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>Semester 1</option>
                                        <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                                    </select>
                                </div>
                                
                                <div class="col-12 text-end">
                                    <div class="filter-actions">
                                        <button type="submit" class="btn btn-primary hover-shadow">
                                            <i class="bx bx-filter-alt me-1"></i> Filter
                                        </button>
                                        <a href="{{ route('subjects.index') }}" class="btn btn-outline-secondary hover-shadow ms-2">
                                            <i class="bx bx-reset me-1"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subjects List -->
        @if($subjects->isEmpty())
            <div class="text-center py-5">
                <img src="https://via.placeholder.com/150" alt="No subjects" class="img-fluid mb-3 opacity-50" style="max-width: 150px;">
                <h5 class="text-muted">Belum ada data mata pelajaran</h5>
                <p class="text-muted">Tambahkan mata pelajaran baru dengan mengklik tombol "Tambah Mata Pelajaran".</p>
                <a href="{{ route('subjects.create') }}" class="btn btn-primary mt-2">
                    <i class="bx bx-plus me-1"></i> Tambah Mata Pelajaran
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">Kode</th>
                            <th width="25%">Nama Mata Pelajaran</th>
                            <th width="10%">Tingkat</th>
                            <th width="10%">Semester</th>
                            <th width="10%">Status</th>
                            <th width="10%">Kurikulum</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $index => $subject)
                            <tr>
                                <td>{{ $index + $subjects->firstItem() }}</td>
                                <td>{{ $subject->code ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('subjects.show', $subject) }}" class="fw-medium text-decoration-none">
                                        {{ $subject->name }}
                                    </a>
                                </td>
                                <td>{{ $subject->class_level ?? 'Semua' }}</td>
                                <td>{{ $subject->semester ? 'Semester '.$subject->semester : 'Semua' }}</td>
                                <td>
                                    @if($subject->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>{{ $subject->curriculum ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('subjects.show', $subject) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Detail">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Edit">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <form action="{{ route('subjects.destroy', $subject) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus mata pelajaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Hapus">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $subjects->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endsection
