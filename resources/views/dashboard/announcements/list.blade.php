@extends('layouts.dashboard')

@section('page-title', 'Pengumuman')

@section('dashboard-content')
<div class="row mb-4">
    <div class="col-lg-9">
        <!-- Debug info card - only visible to admins -->
        @if(auth()->user()->role === 'admin')
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Informasi Debug</h5>
                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#debugInfo">
                    <i class="bx bx-code"></i> Tampilkan/Sembunyikan
                </button>
            </div>
            <div class="collapse" id="debugInfo">
                <div class="card-body">
                    <div class="alert alert-info">
                        <p><strong>Total Pengumuman:</strong> {{ $announcementCount ?? 0 }}</p>
                        <p><strong>Pengumuman Aktif:</strong> {{ $activeCount ?? 0 }}</p>
                    </div>
                    
                    @if(isset($allAnnouncements) && $allAnnouncements->count() > 0)
                    <h6>Semua Pengumuman:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Status</th>
                                    <th>Published</th>
                                    <th>Expired</th>
                                    <th>Aktif?</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allAnnouncements as $ann)
                                <tr>
                                    <td>{{ $ann->title }}</td>
                                    <td>{{ $ann->status }}</td>
                                    <td>{{ $ann->published_at ?? 'NULL' }}</td>
                                    <td>{{ $ann->expired_at ?? 'NULL' }}</td>
                                    <td>{{ $ann->is_active ? 'Ya' : 'Tidak' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Pengumuman Terkini</h5>
            </div>
            <div class="card-body">
                @if(isset($activeAnnouncements) && $activeAnnouncements->count() > 0)
                    <div class="list-group announcement-list">
                        @foreach($activeAnnouncements as $announcement)
                            <div class="list-group-item list-group-item-action border-0 border-bottom py-4 {{ $announcement->priority === 'high' ? 'border-start border-danger border-4' : '' }}">
                                <div class="d-flex align-items-start">
                                    <div class="icon-wrapper me-3 {{ $announcement->priority === 'high' ? 'bg-danger text-white' : 'bg-light text-primary' }} rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bx bx-bell{{ $announcement->priority === 'high' ? '-ring' : '' }}"></i>
                                    </div>
                                    <div class="announcement-content flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h5 class="mb-0">{{ $announcement->title }}</h5>
                                            <div>
                                                @if($announcement->priority === 'high')
                                                    <span class="badge bg-danger">Penting</span>
                                                @elseif($announcement->priority === 'medium')
                                                    <span class="badge bg-info">Sedang</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-muted small mb-2">
                                            <span><i class="bx bx-calendar me-1"></i>{{ $announcement->published_at ? $announcement->published_at->format('d M Y') : now()->format('d M Y') }}</span>
                                            @if($announcement->target_audience !== 'all')
                                                <span class="ms-2"><i class="bx bx-user me-1"></i>Untuk: 
                                                    @switch($announcement->target_audience)
                                                        @case('students')
                                                            Siswa
                                                            @break
                                                        @case('teachers')
                                                            Guru
                                                            @break
                                                        @case('staff')
                                                            Staf
                                                            @break
                                                        @default
                                                            {{ $announcement->target_audience }}
                                                    @endswitch
                                                </span>
                                            @endif
                                            @if($announcement->expired_at)
                                                <span class="ms-2"><i class="bx bx-time me-1"></i>Berakhir: {{ $announcement->expired_at->format('d M Y') }}</span>
                                            @endif
                                        </div>
                                        <div class="announcement-body">
                                            {!! $announcement->content !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <img src="https://via.placeholder.com/150" alt="No announcements" class="img-fluid mb-3 opacity-50" style="max-width: 150px;">
                        <h6 class="text-muted">Tidak ada pengumuman saat ini</h6>
                        <p class="small text-muted">Pengumuman akan ditampilkan di sini ketika tersedia.</p>
                        
                        @if(auth()->user()->role === 'admin')
                        <div class="mt-3">
                            <a href="{{ route('announcements.create') }}" class="btn btn-primary">
                                <i class="bx bx-plus me-1"></i> Buat Pengumuman Baru
                            </a>
                        </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Rest of the sidebar unchanged -->
    <div class="col-lg-3">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Pengumuman Penting</h5>
            </div>
            <div class="card-body p-0">
                @php
                    $highPriority = isset($activeAnnouncements) ? $activeAnnouncements->where('priority', 'high') : collect([]);
                @endphp
                
                @if($highPriority->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($highPriority as $announcement)
                            <div class="list-group-item border-0 border-bottom py-3">
                                <div class="d-flex">
                                    <div class="me-2">
                                        <span class="badge bg-danger d-flex justify-content-center align-items-center" style="width: 24px; height: 24px;">
                                            <i class="bx bx-bell-ring"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ Str::limit($announcement->title, 40) }}</h6>
                                        <div class="small text-muted">{{ $announcement->published_at ? $announcement->published_at->format('d M Y') : now()->format('d M Y') }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-3 text-center">
                        <p class="text-muted small mb-0">Tidak ada pengumuman penting saat ini.</p>
                    </div>
                @endif
            </div>
        </div>
        
        @if(auth()->user()->role === 'admin')
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Menu Admin</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="{{ route('announcements.index') }}" class="list-group-item list-group-item-action">
                        <i class="bx bx-list-ul me-2"></i> Kelola Pengumuman
                    </a>
                    <a href="{{ route('announcements.create') }}" class="list-group-item list-group-item-action">
                        <i class="bx bx-plus me-2"></i> Buat Pengumuman Baru
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    .announcement-body {
        font-size: 0.95rem;
    }
    
    .announcement-body img {
        max-width: 100%;
        height: auto;
    }
    
    .icon-wrapper {
        transition: all 0.3s ease;
    }
    
    .announcement-list .list-group-item:hover .icon-wrapper {
        transform: scale(1.1);
    }
    
    .announcement-list .list-group-item {
        transition: all 0.3s ease;
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
        border: 1px solid #e9ecef;
        padding: 1.25rem;
    }
    
    .announcement-list .list-group-item:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .announcement-list .list-group-item.border-start.border-danger {
        border-left-width: 5px !important;
    }
    
    .icon-wrapper {
        transition: all 0.3s ease;
        width: 48px !important;
        height: 48px !important;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    .announcement-list .list-group-item:hover .icon-wrapper {
        transform: scale(1.1) rotate(5deg);
    }
    
    .announcement-body {
        font-size: 0.95rem;
        line-height: 1.6;
    }
    
    .announcement-body img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
    }
    
    .announcement-date {
        display: inline-block;
        background-color: #f8f9fa;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.8rem;
        margin-right: 0.5rem;
    }
    
    .badge {
        padding: 0.35em 0.65em;
        font-weight: 500;
    }
</style>
@endsection
