@extends('layouts.dashboard')

@section('page-title', 'Detail Pengumuman')

@section('page-actions')
<div class="btn-group">
    <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-sm btn-primary">
        <i class="bx bx-edit me-1"></i> Edit
    </a>
    <a href="{{ route('announcements.index') }}" class="btn btn-sm btn-secondary">
        <i class="bx bx-arrow-back me-1"></i> Kembali
    </a>
</div>
@endsection

@section('dashboard-content')
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $announcement->title }}</h5>
                    <div>
                        {!! $announcement->status_badge !!}
                        {!! $announcement->priority_badge !!}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="announcement-content mb-4">
                    {!! $announcement->content !!}
                </div>
            </div>
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center small text-muted">
                    <div>
                        <i class="bx bx-calendar me-1"></i> Dibuat: {{ $announcement->created_at->format('d M Y H:i') }}
                    </div>
                    <div>
                        <i class="bx bx-user me-1"></i> Oleh: {{ $announcement->creator ? $announcement->creator->name : 'System' }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="d-flex gap-2 mb-3">
            <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-outline-primary flex-grow-1">
                <i class="bx bx-edit me-1"></i> Edit Pengumuman
            </a>
            <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bx bx-trash me-1"></i> Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Publication Info -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Informasi Publikasi</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 status-list-item">
                        <span>Status</span>
                        <span>{!! $announcement->status_badge !!}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 status-list-item">
                        <span>Prioritas</span>
                        <span>{!! $announcement->priority_badge !!}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 status-list-item">
                        <span>Target</span>
                        <span>
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
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 status-list-item">
                        <span>Tanggal Publikasi</span>
                        <span>{{ $announcement->published_at ? $announcement->published_at->format('d M Y H:i') : '-' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 status-list-item">
                        <span>Tanggal Kadaluarsa</span>
                        <span>{{ $announcement->expired_at ? $announcement->expired_at->format('d M Y H:i') : 'Tidak Ada' }}</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Change Status Card -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Ubah Status</h5>
            </div>
            <div class="card-body">
                <p class="card-text small text-muted">Ubah status pengumuman ini secara cepat tanpa perlu mengedit semua data.</p>
                
                <div class="d-flex gap-2 mt-3">
                    @if($announcement->status !== 'published')
                    <form action="{{ route('announcements.change-status', $announcement) }}" method="POST" class="flex-grow-1">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="published">
                        <button type="submit" class="btn btn-success btn-sm w-100 action-btn">
                            <i class="bx bx-check-circle me-1"></i> Publikasikan
                        </button>
                    </form>
                    @endif
                    
                    @if($announcement->status !== 'draft')
                    <form action="{{ route('announcements.change-status', $announcement) }}" method="POST" class="flex-grow-1">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="draft">
                        <button type="submit" class="btn btn-warning btn-sm w-100 action-btn">
                            <i class="bx bx-edit me-1"></i> Jadikan Draft
                        </button>
                    </form>
                    @endif
                    
                    @if($announcement->status !== 'archived')
                    <form action="{{ route('announcements.change-status', $announcement) }}" method="POST" class="flex-grow-1">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="archived">
                        <button type="submit" class="btn btn-secondary btn-sm w-100 action-btn">
                            <i class="bx bx-archive me-1"></i> Arsipkan
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Preview Card -->
        <div class="card shadow-sm preview-card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Preview</h5>
            </div>
            <div class="card-body">
                <p class="card-text small text-muted">Ini adalah tampilan pengumuman seperti yang akan dilihat oleh pengguna:</p>
                
                <div class="border rounded p-3 mt-3 bg-light">
                    <h5 class="mb-2">{{ $announcement->title }}</h5>
                    <div class="small content-preview">
                        {!! Str::limit(strip_tags($announcement->content), 150) !!}
                    </div>
                    <div class="mt-2 d-flex justify-content-between">
                        <small class="text-muted">{{ $announcement->published_at ? $announcement->published_at->format('d M Y') : 'Belum dipublikasikan' }}</small>
                        @if($announcement->priority === 'high')
                            <span class="badge bg-danger">Penting</span>
                        @endif
                    </div>
                </div>
                
                <div class="mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="visibilityCheck" {{ $announcement->is_active ? 'checked' : '' }} disabled>
                        <label class="form-check-label" for="visibilityCheck">
                            Terlihat oleh pengguna
                        </label>
                    </div>
                    @if(!$announcement->is_active)
                    <small class="text-danger">Pengumuman ini tidak terlihat oleh pengguna karena 
                        @if($announcement->status !== 'published')
                            statusnya bukan "Dipublikasikan"
                        @elseif($announcement->expired_at && $announcement->expired_at < now())
                            sudah melewati tanggal kadaluarsa
                        @elseif($announcement->published_at > now())
                            belum mencapai tanggal publikasi
                        @endif
                    </small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .announcement-content {
        line-height: 1.7;
        font-size: 1.05rem;
    }
    
    .announcement-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.75rem;
        margin: 1.5rem 0;
    }
    
    .announcement-content p {
        margin-bottom: 1.25rem;
    }
    
    .announcement-content ul, 
    .announcement-content ol {
        margin-bottom: 1.25rem;
        padding-left: 1.5rem;
    }
    
    .announcement-content h1, 
    .announcement-content h2, 
    .announcement-content h3,
    .announcement-content h4,
    .announcement-content h5,
    .announcement-content h6 {
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }
    
    .content-preview {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }
    
    .preview-card {
        transition: all 0.3s ease;
    }
    
    .preview-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
    
    .status-list-item {
        transition: all 0.2s ease;
    }
    
    .status-list-item:hover {
        background-color: #f8f9fa;
    }
    
    .action-btn {
        transition: all 0.2s ease;
    }
    
    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection
