@extends('layouts.dashboard')

@section('page-title', 'Data Siswa')

@section('page-actions')
<a href="{{ route('students.create') }}" class="btn btn-sm btn-outline-primary">
    <i class="bx bx-plus me-1"></i> Tambah Siswa
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-semibold">Daftar Siswa</h5>
        <div>
            <input type="text" class="form-control form-control-sm" placeholder="Cari siswa..." id="searchInput">
        </div>
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
                        <th>NIS</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Tahun Akademik</th>
                        <th>Jenis Kelamin</th>
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
                        <td>{{ $student->nis }}</td>
                        <td>{{ $student->nisn }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->class }}</td>
                        <td>{{ $student->academic_year }}</td>
                        <td>{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Edit">
                                    <i class="bx bx-edit-alt"></i>
                                </a>
                                <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun siswa ini?')">
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

@section('scripts')
<script>
    // Simple search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('tbody tr');
        
        searchInput.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? '' : 'none';
            });
        });
    });
</script>
@endsection
@endsection 