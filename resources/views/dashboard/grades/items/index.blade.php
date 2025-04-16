@extends('layouts.dashboard')

@section('page-title', 'Item Nilai Akademik')

@section('page-actions')
<a href="{{ route('grade-items.create') }}" class="btn btn-primary btn-sm">
    <i class="bx bx-plus me-1"></i> Tambah Item Nilai
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
                <h6 class="card-title mb-3">Filter Item Nilai</h6>
                <form action="{{ route('grade-items.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="category_id" class="form-label">Kategori Nilai</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} - {{ $category->subject->name ?? 'N/A' }} ({{ $category->class->name ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">Tanggal Dari</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">Tanggal Sampai</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
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
                        <th>Nama Item</th>
                        <th>Kategori</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Tanggal</th>
                        <th>Nilai Maks</th>
                        <th class="text-center" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gradeItems as $index => $item)
                    <tr>
                        <td>{{ $index + $gradeItems->firstItem() }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->gradeCategory->name }}</td>
                        <td>{{ $item->gradeCategory->subject->name ?? 'N/A' }}</td>
                        <td>{{ $item->gradeCategory->class->name ?? 'N/A' }}</td>
                        <td>{{ $item->date->format('d M Y') }}</td>
                        <td>{{ $item->max_score }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('grade-items.show', $item) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('grade-items.enter-grades', $item) }}" class="btn btn-sm btn-outline-success" title="Input Nilai">
                                    <i class="bx bx-pencil"></i>
                                </a>
                                <a href="{{ route('grade-items.edit', $item) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('grade-items.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus item nilai ini?')">
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
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="bx bx-folder-open mb-2" style="font-size: 2rem;"></i>
                            <p class="mb-0">Tidak ada data item nilai</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $gradeItems->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize select2 if available
        if (typeof $.fn.select2 !== 'undefined') {
            $('#category_id').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        }
    });
</script>
@endsection
