@extends('layouts.dashboard')

@section('page-title', 'Detail Kategori Nilai')

@section('page-actions')
<div class="d-flex">
    <a href="{{ route('grade-items.create', ['category_id' => $gradeCategory->id]) }}" class="btn btn-primary btn-sm me-2">
        <i class="bx bx-plus me-1"></i> Tambah Item Nilai
    </a>
    <a href="{{ route('grade-categories.index') }}" class="btn btn-secondary btn-sm">
        <i class="bx bx-arrow-back me-1"></i> Kembali
    </a>
</div>
@endsection

@section('dashboard-content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Informasi Kategori Nilai</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Nama Kategori</span>
                        <span class="fw-medium">{{ $gradeCategory->name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Mata Pelajaran</span>
                        <span class="fw-medium">{{ $gradeCategory->subject->name ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Kelas</span>
                        <span class="fw-medium">{{ $gradeCategory->class->name ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Bobot</span>
                        <span class="fw-medium">{{ $gradeCategory->weight }}%</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Tahun Akademik</span>
                        <span class="fw-medium">{{ $gradeCategory->academic_year }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Semester</span>
                        <span class="fw-medium">{{ $gradeCategory->semester }}</span>
                    </li>
                    <li class="list-group-item px-0">
                        <span class="text-muted">Deskripsi</span>
                        <p class="mb-0 mt-1">{{ $gradeCategory->description ?? 'Tidak ada deskripsi' }}</p>
                    </li>
                </ul>
            </div>
            <div class="card-footer bg-white">
                <div class="d-grid gap-2">
                    <a href="{{ route('grade-categories.edit', $gradeCategory) }}" class="btn btn-outline-primary">
                        <i class="bx bx-edit me-1"></i> Edit Kategori
                    </a>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bx bx-trash me-1"></i> Hapus Kategori
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Item Nilai dalam Kategori {{ $gradeCategory->name }}</h5>
                <a href="{{ route('grade-items.create', ['category_id' => $gradeCategory->id]) }}" class="btn btn-sm btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Item Nilai
                </a>
            </div>
            <div class="card-body">
                @if($gradeCategory->gradeItems->isEmpty())
                    <div class="text-center py-5">
                        <i class="bx bx-folder-open text-muted" style="font-size: 3rem;"></i>
                        <h6 class="mt-3">Belum ada item nilai</h6>
                        <p class="text-muted">Tambahkan item nilai untuk kategori ini</p>
                        <a href="{{ route('grade-items.create', ['category_id' => $gradeCategory->id]) }}" class="btn btn-sm btn-primary">
                            <i class="bx bx-plus me-1"></i> Tambah Sekarang
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Item</th>
                                    <th>Tanggal</th>
                                    <th>Nilai Maksimal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gradeCategory->gradeItems as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->name }}</td>
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
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kategori nilai <strong>{{ $gradeCategory->name }}</strong>?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan dan akan menghapus semua item nilai dan nilai siswa terkait.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('grade-categories.destroy', $gradeCategory) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
