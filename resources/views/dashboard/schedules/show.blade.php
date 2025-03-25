@extends('layouts.dashboard')

@section('page-title', 'Detail Jadwal Pelajaran')

@section('page-actions')
<div class="d-flex">
    <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-primary btn-sm me-2">
        <i class="bx bx-edit me-1"></i> Edit
    </a>
    <a href="{{ route('schedules.index') }}" class="btn btn-secondary btn-sm">
        <i class="bx bx-arrow-back me-1"></i> Kembali
    </a>
</div>
@endsection

@section('dashboard-content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $schedule->subject }}</h5>
                    <div>
                        @if($schedule->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Tidak Aktif</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex flex-wrap small text-muted mb-3">
                        <div class="me-4">
                            <i class="bx bx-calendar-event me-1"></i> {{ $schedule->formatted_day }}
                        </div>
                        <div class="me-4">
                            <i class="bx bx-time me-1"></i> {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                        </div>
                        @if($schedule->room)
                        <div class="me-4">
                            <i class="bx bx-map me-1"></i> {{ $schedule->room }}
                        </div>
                        @endif
                        @if($schedule->academic_year)
                        <div class="me-4">
                            <i class="bx bx-book me-1"></i> Tahun Akademik: {{ $schedule->academic_year }}
                        </div>
                        @endif
                        @if($schedule->semester)
                        <div class="me-4">
                            <i class="bx bx-time me-1"></i> Semester {{ $schedule->semester }}
                        </div>
                        @endif
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light border-0 h-100">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="bx bxs-school me-1"></i> Kelas</h6>
                                    <p class="mb-0 fs-5">{{ $schedule->class ? $schedule->class->name : '-' }}</p>
                                    @if($schedule->class && $schedule->class->level)
                                        <small class="text-muted">Level: {{ $schedule->class->level }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light border-0 h-100">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="bx bxs-user-badge me-1"></i> Guru</h6>
                                    <p class="mb-0 fs-5">{{ $schedule->teacher ? $schedule->teacher->name : '-' }}</p>
                                    @if($schedule->teacher && $schedule->teacher->email)
                                        <small class="text-muted">{{ $schedule->teacher->email }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($schedule->description)
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Deskripsi</h6>
                            <p>{{ $schedule->description }}</p>
                        </div>
                    @endif
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div class="small text-muted">
                        <i class="bx bx-user-circle me-1"></i> Dibuat oleh: {{ $schedule->creator ? $schedule->creator->name : 'System' }}
                    </div>
                    <div class="small text-muted">
                        <i class="bx bx-calendar-check me-1"></i> Dibuat: {{ $schedule->created_at->format('d M Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="d-flex gap-2 mb-4">
            <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-outline-primary flex-grow-1">
                <i class="bx bx-edit me-1"></i> Edit Jadwal
            </a>
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="bx bx-trash me-1"></i> Hapus
            </button>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Status Card -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Status Jadwal</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Status</span>
                        <span>
                            @if($schedule->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Tahun Akademik</span>
                        <span>{{ $schedule->academic_year ?: '-' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Semester</span>
                        <span>{{ $schedule->semester ?: '-' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Notifikasi</span>
                        <span>
                            @if($schedule->notification_enabled)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </span>
                    </li>
                    @if($schedule->notification_enabled)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Pengingat</span>
                            <span>{{ $schedule->notify_minutes_before }} menit sebelumnya</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Metode Notifikasi</span>
                            <span>
                                @if($schedule->notify_by_email && $schedule->notify_by_push)
                                    Email & Push
                                @elseif($schedule->notify_by_email)
                                    Email
                                @elseif($schedule->notify_by_push)
                                    Push
                                @else
                                    -
                                @endif
                            </span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Related Schedules Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Jadwal Terkait</h5>
            </div>
            <div class="card-body">
                @php
                    // Find related schedules (same class or same teacher on different days)
                    $relatedSchedules = \App\Models\ClassSchedule::where('id', '!=', $schedule->id)
                        ->where(function($query) use ($schedule) {
                            $query->where('class_id', $schedule->class_id)
                                ->orWhere('teacher_id', $schedule->teacher_id);
                        })
                        ->take(5)
                        ->get();
                @endphp

                @if($relatedSchedules->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($relatedSchedules as $relatedSchedule)
                            <a href="{{ route('schedules.show', $relatedSchedule) }}" class="list-group-item list-group-item-action px-0">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $relatedSchedule->subject }}</h6>
                                    <small>{{ $relatedSchedule->formatted_day }}</small>
                                </div>
                                <small class="text-muted">
                                    <i class="bx bx-time me-1"></i> {{ $relatedSchedule->start_time->format('H:i') }} - {{ $relatedSchedule->end_time->format('H:i') }}
                                </small>
                                <div class="small text-muted">
                                    @if($relatedSchedule->class_id == $schedule->class_id)
                                        <span class="badge bg-light text-dark"><i class="bx bxs-school me-1"></i> Kelas yang sama</span>
                                    @endif
                                    @if($relatedSchedule->teacher_id == $schedule->teacher_id)
                                        <span class="badge bg-light text-dark"><i class="bx bxs-user-badge me-1"></i> Guru yang sama</span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center mb-0">Tidak ada jadwal terkait yang ditemukan.</p>
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
                <h5 class="modal-title" id="deleteModalLabel">Hapus Jadwal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus jadwal mata pelajaran <strong>{{ $schedule->subject }}</strong>?</p>
                <p class="text-danger small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('schedules.destroy', $schedule) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.125);
    }
</style>
@endsection
