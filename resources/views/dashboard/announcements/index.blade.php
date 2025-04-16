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
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bx bx-check-circle fs-4 me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Filters -->
        <div class="row mb-3 g-2">
            <div class="col-lg-4 col-md-5 col-sm-12">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light">
                        <i class="bx bx-search"></i>
                    </span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Cari pengumuman...">
                    <button class="btn btn-outline-secondary" type="button" id="clearSearch" title="Hapus pencarian">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
            </div>
            <div class="col-lg-4 col-md-3 col-sm-6">
                <select class="form-select form-select-sm" id="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="published">Dipublikasikan</option>
                    <option value="draft">Draft</option>
                    <option value="archived">Diarsipkan</option>
                </select>
            </div>
            <div class="col-lg-4 col-md-3 col-sm-6">
                <select class="form-select form-select-sm" id="priorityFilter">
                    <option value="">Semua Prioritas</option>
                    <option value="high">Penting</option>
                    <option value="medium">Sedang</option>
                    <option value="low">Rendah</option>
                </select>
            </div>
        </div>

        <!-- Announcements Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle border-bottom">
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
                    <tr class="announcement-row" data-status="{{ $announcement->status }}" data-priority="{{ $announcement->priority }}">
                        <td class="fw-medium">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold text-primary">{{ $announcement->title }}</span>
                                <small class="text-muted mt-1">{{ \Illuminate\Support\Str::limit(strip_tags($announcement->content), 50) }}</small>
                                
                                @if($announcement->priority == 'high')
                                <span class="badge bg-danger mt-2 align-self-start">Penting</span>
                                @elseif($announcement->priority == 'medium')
                                <span class="badge bg-warning text-dark mt-2 align-self-start">Sedang</span>
                                @elseif($announcement->priority == 'low')
                                <span class="badge bg-info mt-2 align-self-start">Rendah</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($announcement->status == 'published')
                            <span class="badge bg-success rounded-pill px-3 py-2"><i class="bx bx-check-circle me-1"></i> Dipublikasikan</span>
                            @elseif($announcement->status == 'draft')
                            <span class="badge bg-secondary rounded-pill px-3 py-2"><i class="bx bx-edit me-1"></i> Draft</span>
                            @elseif($announcement->status == 'archived')
                            <span class="badge bg-dark rounded-pill px-3 py-2"><i class="bx bx-archive me-1"></i> Diarsipkan</span>
                            @endif
                        </td>
                        <td>
                            @if($announcement->published_at)
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-calendar text-muted me-1"></i>
                                    <span>{{ $announcement->published_at->format('d M Y H:i') }}</span>
                                </div>
                                @if($announcement->expired_at)
                                    <div class="small text-muted mt-1 d-flex align-items-center">
                                        <i class="bx bx-time-five me-1"></i>
                                        <span>Berakhir: {{ $announcement->expired_at->format('d M Y') }}</span>
                                    </div>
                                @endif
                            @else
                                <span class="text-muted fst-italic">-</span>
                            @endif
                        </td>
                        <td>
                            @switch($announcement->target_audience)
                                @case('all')
                                    <span class="badge bg-info rounded-pill px-3 py-2"><i class="bx bx-group me-1"></i> Semua</span>
                                    @break
                                @case('students')
                                    <span class="badge bg-primary rounded-pill px-3 py-2"><i class="bx bx-user-pin me-1"></i> Siswa</span>
                                    @break
                                @case('teachers')
                                    <span class="badge bg-success rounded-pill px-3 py-2"><i class="bx bx-chalkboard me-1"></i> Guru</span>
                                    @break
                                @case('staff')
                                    <span class="badge bg-secondary rounded-pill px-3 py-2"><i class="bx bx-briefcase me-1"></i> Staf</span>
                                    @break
                                @default
                                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">{{ $announcement->target_audience }}</span>
                            @endswitch
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xs me-2 bg-light rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bx bx-user text-primary"></i>
                                </div>
                                <div>
                                    <span class="fw-medium">{{ $announcement->creator ? $announcement->creator->name : 'System' }}</span>
                                    <div class="small text-muted">{{ $announcement->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
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
                        <td colspan="7" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <i class="bx bx-bell-off text-muted" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 mb-1">Belum ada pengumuman</h5>
                                <p class="text-muted mb-3">Buat pengumuman baru untuk ditampilkan di sini</p>
                                <a href="{{ route('announcements.create') }}" class="btn btn-primary">
                                    <i class="bx bx-plus me-1"></i> Buat Pengumuman
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="small text-muted">
                Menampilkan {{ $announcements->firstItem() ?? 0 }} sampai {{ $announcements->lastItem() ?? 0 }} dari {{ $announcements->total() }} pengumuman
            </div>
            <div class="pagination-container">
                {{ $announcements->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .announcement-row {
        transition: all 0.2s ease;
    }
    .announcement-row:hover {
        background-color: rgba(var(--primary-rgb), 0.05);
    }
    .avatar {
        width: 28px;
        height: 28px;
    }
    .badge {
        font-weight: 500;
    }
    .table td {
        padding-top: 0.8rem;
        padding-bottom: 0.8rem;
    }
    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }
    .file-path {
        max-width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
        vertical-align: middle;
    }

    /* Custom Pagination Styling */
    .pagination {
        margin-bottom: 0;
    }
    .pagination .page-item .page-link {
        padding: 0.4rem 0.65rem;
        font-size: 0.85rem;
        border-radius: 0.25rem;
        color: var(--primary);
    }
    .pagination .page-item.active .page-link {
        background-color: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }
    .pagination .page-item .page-link:focus {
        box-shadow: 0 0 0 0.15rem rgba(var(--primary-rgb), 0.25);
    }
    .pagination-container .pagination {
        flex-wrap: wrap;
        justify-content: center;
    }
    .pagination-container nav {
        display: inline-block;
    }
    @media (max-width: 576px) {
        .pagination .page-link {
            padding: 0.35rem 0.5rem;
            font-size: 0.8rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Status and priority filters
        const statusFilter = document.getElementById('statusFilter');
        const priorityFilter = document.getElementById('priorityFilter');
        const searchInput = document.getElementById('searchInput');
        const clearSearchBtn = document.getElementById('clearSearch');
        const tableRows = document.querySelectorAll('.announcement-row');
        
        // Function to shorten file paths for display
        function shortenPath(path, maxLength = 30) {
            if (!path || path.length <= maxLength) return path;
            
            const pathParts = path.split('\\');
            const fileName = pathParts.pop();
            const dirName = pathParts.pop();
            
            return '...' + '\\' + dirName + '\\' + fileName;
        }
        
        // Apply to any path elements
        document.querySelectorAll('.file-path').forEach(element => {
            const fullPath = element.getAttribute('data-full-path');
            if (fullPath) {
                element.textContent = shortenPath(fullPath);
                element.title = fullPath; // Full path on hover
            }
        });
        
        function filterTable() {
            const statusValue = statusFilter.value;
            const priorityValue = priorityFilter.value;
            const searchValue = searchInput.value.toLowerCase().trim();
            
            tableRows.forEach(row => {
                const rowStatus = row.dataset.status;
                const rowPriority = row.dataset.priority;
                const titleElement = row.querySelector('.fw-semibold');
                const title = titleElement ? titleElement.textContent.toLowerCase() : '';
                
                const matchesStatus = statusValue === '' || rowStatus === statusValue;
                const matchesPriority = priorityValue === '' || rowPriority === priorityValue;
                const matchesSearch = searchValue === '' || title.includes(searchValue);
                
                if (matchesStatus && matchesPriority && matchesSearch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        // Clear search input
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            filterTable();
        });
        
        statusFilter.addEventListener('change', filterTable);
        priorityFilter.addEventListener('change', filterTable);
        searchInput.addEventListener('input', filterTable);
    });
</script>
@endsection
