@extends('layouts.dashboard')

@section('page-title', 'Kategori Nilai Akademik')

@section('page-actions')
<a href="{{ route('grade-categories.create') }}" class="btn btn-primary btn-sm">
    <i class="bx bx-plus me-1"></i> Tambah Kategori Nilai
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm">
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="card bg-light border-0 mb-4">
            <div class="card-body">
                <h6 class="card-title mb-3">Filter Kategori Nilai</h6>
                <form action="{{ route('grade-categories.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="subject_id" class="form-label">Mata Pelajaran</label>
                        <select class="form-select" id="subject_id" name="subject_id">
                            <option value="">Semua Mata Pelajaran</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="class_id" class="form-label">Kelas</label>
                        <select class="form-select" id="class_id" name="class_id">
                            <option value="">Semua Kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="academic_year" class="form-label">Tahun Akademik</label>
                        <select class="form-select" id="academic_year" name="academic_year">
                            <option value="">Semua</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="semester" class="form-label">Semester</label>
                        <select class="form-select" id="semester" name="semester">
                            <option value="">Semua</option>
                            <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>Semester 1</option>
                            <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bx bx-filter-alt me-1"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Nama Kategori</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Bobot</th>
                        <th>Tahun / Semester</th>
                        <th class="text-center" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gradeCategories as $index => $category)
                    <tr>
                        <td>{{ $index + $gradeCategories->firstItem() }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->subject->name ?? 'N/A' }}</td>
                        <td>{{ $category->class->name ?? 'N/A' }}</td>
                        <td>{{ $category->weight }}%</td>
                        <td>{{ $category->academic_year }} / {{ $category->semester }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('grade-categories.show', $category) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('grade-categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('grade-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bx bx-folder-open mb-2" style="font-size: 2rem;"></i>
                            <p class="mb-0">Tidak ada data kategori nilai</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $gradeCategories->withQueryString()->links() }}
        </div>
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
