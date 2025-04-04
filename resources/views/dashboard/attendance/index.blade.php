@extends('layouts.dashboard')

@section('page-title', 'Manajemen Absensi')

@section('page-actions')
<a href="{{ route('attendance.create') }}" class="btn btn-primary btn-sm">
    <i class="bx bx-plus me-1"></i> Absensi Baru
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <!-- Success Message -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bx bx-check-circle fs-4 me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-light border-0">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Filter Absensi</h6>
                        <form action="{{ route('attendance.index') }}" method="GET" class="row g-3">
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
                                <label for="date" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ request('date', date('Y-m-d')) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="subject_id" class="form-label">Mata Pelajaran</label>
                                <select class="form-select" id="subject_id" name="subject_id">
                                    <option value="">Semua Mata Pelajaran</option>
                                    @foreach($subjects ?? [] as $subject)
                                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-filter-alt me-1"></i> Filter
                                </button>
                                <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary ms-2">
                                    <i class="bx bx-reset me-1"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Records Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Jam</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendanceSessions ?? [] as $session)
                    <tr>
                        <td>{{ $session->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($session->date)->format('d M Y') }}</td>
                        <td>{{ $session->class->name ?? 'N/A' }}</td>
                        <td>{{ $session->subject->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</td>
                        <td>
                            <span class="badge bg-{{ $session->is_completed ? 'success' : 'warning' }}">
                                {{ $session->is_completed ? 'Selesai' : 'Belum Selesai' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('attendance.show', $session->id) }}" class="btn btn-outline-primary">
                                    <i class="bx bx-show"></i>
                                </a>
                                @if(!$session->is_completed)
                                <a href="{{ route('attendance.edit', $session->id) }}" class="btn btn-outline-secondary">
                                    <i class="bx bx-edit"></i>
                                </a>
                                @endif
                                <button type="button" class="btn btn-outline-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal" 
                                        data-id="{{ $session->id }}"
                                        data-date="{{ \Carbon\Carbon::parse($session->date)->format('d M Y') }}"
                                        data-class="{{ $session->class->name ?? 'N/A' }}">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bx bx-calendar-x fs-3 d-block mb-2"></i>
                            Tidak ada data absensi yang ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $attendanceSessions->links() ?? '' }}
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Hapus Sesi Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data absensi ini?</p>
                <p><strong>Tanggal:</strong> <span id="date-text"></span></p>
                <p><strong>Kelas:</strong> <span id="class-text"></span></p>
                <p class="text-danger small">Tindakan ini akan menghapus semua data kehadiran siswa pada sesi ini dan tidak dapat dibatalkan.</p>
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
        // Setup for delete modal
        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const date = button.getAttribute('data-date');
                const className = button.getAttribute('data-class');
                
                document.getElementById('date-text').textContent = date;
                document.getElementById('class-text').textContent = className;
                document.getElementById('deleteForm').action = `{{ url('attendance') }}/${id}`;
            });
        }
    });
</script>
@endsection
