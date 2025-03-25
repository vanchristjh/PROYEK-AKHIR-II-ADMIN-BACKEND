@extends('layouts.dashboard')

@section('page-title', 'Manajemen Pengumuman')

@section('page-actions')
<a href="{{ route('announcements.create') }}" class="btn btn-primary btn-sm">
    <i class="bx bx-plus me-1"></i> Buat Pengumuman
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <!-- Success Message -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Search & Filters -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control border-start-0" id="searchInput" placeholder="Cari pengumuman...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="published">Dipublikasikan</option>
                    <option value="draft">Draft</option>
                    <option value="archived">Diarsipkan</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="priorityFilter">
                    <option value="">Semua Prioritas</option>
                    <option value="high">Penting</option>
                    <option value="medium">Sedang</option>
                    <option value="low">Rendah</option>
                </select>
            </div>
        </div>

        <!-- Announcements Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 30%">Judul</th>
                        <th style="width: 15%">Status</th>
                        <th style="width: 15%">Tanggal Publikasi</th>
                        <th style="width: 15%">Target</th>
                        <th style="width: 15%">Dibuat Oleh</th>
                        <th style="width: 10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $index => $announcement)
                    <tr data-status="{{ $announcement->status }}" data-priority="{{ $announcement->priority }}">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-semibold">{{ $announcement->title }}</div>
                            <small class="text-muted">{{ Str::limit(strip_tags($announcement->content), 50) }}</small>
                            {!! $announcement->priority_badge !!}
                        </td>
                        <td>{!! $announcement->status_badge !!}</td>
                        <td>
                            @if($announcement->published_at)
                                {{ $announcement->published_at->format('d M Y H:i') }}
                                @if($announcement->expired_at)
                                    <div class="small text-muted">
                                        Berakhir: {{ $announcement->expired_at->format('d M Y') }}
                                    </div>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @switch($announcement->target_audience)
                                @case('all')
                                    <span class="badge bg-info">Semua</span>
                                    @break
                                @case('students')
                                    <span class="badge bg-primary">Siswa</span>
                                    @break
                                @case('teachers')
                                    <span class="badge bg-success">Guru</span>
                                    @break
                                @case('staff')
                                    <span class="badge bg-secondary">Staf</span>
                                    @break
                                @default
                                    <span class="badge bg-light text-dark">{{ $announcement->target_audience }}</span>
                            @endswitch
                        </td>
                        <td>
                            {{ $announcement->creator ? $announcement->creator->name : 'System' }}
                            <div class="small text-muted">{{ $announcement->created_at->format('d M Y') }}</div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('announcements.show', $announcement) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bx bx-edit-alt"></i>
                                </a>
                                <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">
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
                        <td colspan="7" class="text-center py-4">
                            <img src="https://via.placeholder.com/80x80" alt="No data" class="img-fluid mb-3 opacity-50" style="max-width: 80px;">
                            <p class="text-muted">Belum ada pengumuman</p>
                            <a href="{{ route('announcements.create') }}" class="btn btn-sm btn-primary">
                                <i class="bx bx-plus me-1"></i> Buat Pengumuman
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $announcements->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const priorityFilter = document.getElementById('priorityFilter');
        const tableRows = document.querySelectorAll('tbody tr');
        
        function filterTable() {
            const searchValue = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value;
            const priorityValue = priorityFilter.value;
            
            tableRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                const rowStatus = row.dataset.status;
                const rowPriority = row.dataset.priority;
                
                const matchesSearch = rowText.includes(searchValue);
                const matchesStatus = statusValue === '' || rowStatus === statusValue;
                const matchesPriority = priorityValue === '' || rowPriority === priorityValue;
                
                if (matchesSearch && matchesStatus && matchesPriority) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        searchInput.addEventListener('keyup', filterTable);
        statusFilter.addEventListener('change', filterTable);
        priorityFilter.addEventListener('change', filterTable);
    });
</script>
@endsection
