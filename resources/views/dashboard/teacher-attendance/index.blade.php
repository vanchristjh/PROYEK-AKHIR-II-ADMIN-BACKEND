@extends('layouts.dashboard')

@section('page-title', 'Data Absensi Guru')

@section('page-actions')
<a href="{{ route('teacher-attendance.create') }}" class="btn btn-primary btn-sm">
    <i class="bx bx-plus me-1"></i> Buat Absensi Baru
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm mb-4 border-0 overflow-hidden">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Jenis Kegiatan</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendanceSessions as $session)
                    <tr>
                        <td>{{ Carbon\Carbon::parse($session->date)->format('d M Y') }}</td>
                        <td>{{ Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ Carbon\Carbon::parse($session->end_time)->format('H:i') }}</td>
                        <td>{{ $session->activity_type ?? 'Umum' }}</td>
                        <td>
                            @if($session->is_completed)
                            <span class="badge bg-success">Selesai</span>
                            @else
                            <span class="badge bg-warning">Belum Selesai</span>
                            @endif
                        </td>
                        <td>{{ Str::limit($session->notes, 50) }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('teacher-attendance.show', $session->id) }}" class="btn btn-light" title="Detail">
                                    <i class="bx bx-show"></i>
                                </a>
                                @if(!$session->is_completed)
                                <a href="{{ route('teacher-attendance.edit', $session->id) }}" class="btn btn-light" title="Edit">
                                    <i class="bx bx-edit"></i>
                                </a>
                                @endif
                                <button type="button" class="btn btn-light" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $session->id }}">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                            
                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $session->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah Anda yakin ingin menghapus data absensi guru pada tanggal <strong>{{ Carbon\Carbon::parse($session->date)->format('d M Y') }}</strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('teacher-attendance.destroy', $session->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <div class="d-flex flex-column align-items-center">
                                <i class="bx bx-calendar-x fs-1 mb-2"></i>
                                <h6>Belum ada data absensi guru</h6>
                                <p class="mb-0">Klik tombol "Buat Absensi Baru" untuk mulai mencatat absensi guru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-end mt-3">
            {{ $attendanceSessions->links() }}
        </div>
    </div>
</div>
@endsection
