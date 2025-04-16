@extends('layouts.dashboard')

@section('page-title', 'Data Guru')

@section('page-actions')
<a href="{{ route('teachers.create') }}" class="btn btn-sm btn-outline-primary">
    <i class="bx bx-plus me-1"></i> Tambah Guru
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-semibold">Daftar Guru</h5>
    </div>
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover border">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th class="text-center" width="8%">Foto</th>
                        <th>NIP</th>
                        <th>NUPTK</th>
                        <th>Nama</th>
                        <th>Mata Pelajaran</th>
                        <th>Jabatan</th>
                        <th>Pendidikan</th>
                        <th class="text-center" width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($teachers as $index => $teacher)
                    <tr>
                        <td class="text-center">{{ $index + $teachers->firstItem() }}</td>
                        <td class="text-center">
                            @if($teacher->profile_photo)
                                <img src="{{ asset('storage/'.$teacher->profile_photo) }}" alt="{{ $teacher->name }}" class="rounded-circle" width="40" height="40">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}&background=2d4059&color=fff&size=40" alt="{{ $teacher->name }}" class="rounded-circle" width="40" height="40">
                            @endif
                        </td>
                        <td>{{ $teacher->nip }}</td>
                        <td>{{ $teacher->nuptk }}</td>
                        <td>{{ $teacher->name }}</td>
                        <td>{{ $teacher->subject }}</td>
                        <td>{{ $teacher->position }}</td>
                        <td>{{ $teacher->education_level }}</td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Edit">
                                    <i class="bx bx-edit-alt"></i>
                                </a>
                                <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun guru ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Hapus">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="bx bx-error-circle fs-3 d-block mb-2"></i>
                            Belum ada data guru
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            {{ $teachers->links() }}
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
                    <a href="{{ route('teachers.export.excel') }}" class="btn btn-outline-success">
                        <i class="bx bx-file-excel me-1"></i> Ekspor ke Excel
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-grid">
                    <a href="{{ route('teachers.export.pdf') }}" class="btn btn-outline-danger">
                        <i class="bx bx-file-pdf me-1"></i> Ekspor ke PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection