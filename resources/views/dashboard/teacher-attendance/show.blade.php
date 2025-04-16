@extends('layouts.dashboard')

@php
    // Define $attendance as an alias for $session or $teacherAttendance to fix undefined variable error
    $attendance = $session ?? $teacherAttendance ?? null;
@endphp

@section('page-title', 'Detail Absensi Guru')

@section('page-actions')
<div class="btn-group btn-group-sm">
    <a href="{{ route('teacher-attendance.index') }}" class="btn btn-secondary">
        <i class="bx bx-arrow-back me-1"></i> Kembali
    </a>
    @if(!$attendance->is_completed)
    <a href="{{ route('teacher-attendance.edit', $attendance->id) }}" class="btn btn-primary">
        <i class="bx bx-edit me-1"></i> Edit
    </a>
    @endif
</div>
@endsection

@section('dashboard-content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Session Info -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-light border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <p class="mb-1 text-muted small">Tanggal</p>
                                <h6 class="fw-bold">{{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</h6>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1 text-muted small">Waktu</p>
                                <h6 class="fw-bold">{{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}</h6>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1 text-muted small">Jenis Kegiatan</p>
                                <h6 class="fw-bold">{{ $attendance->activity_type ?? 'Umum' }}</h6>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1 text-muted small">Status</p>
                                <h6 class="fw-bold">
                                    @if($attendance->is_completed)
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-warning">Belum Selesai</span>
                                    @endif
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Summary -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Ringkasan Absensi</h5>
                        <div class="row">
                            <div class="col-md-2 col-6 mb-3">
                                <div class="text-center py-3 border rounded bg-success bg-opacity-10">
                                    <div class="fs-4 fw-bold text-success">{{ $attendanceSummary['hadir'] ?? 0 }}</div>
                                    <div class="small">Hadir</div>
                                </div>
                            </div>
                            <div class="col-md-2 col-6 mb-3">
                                <div class="text-center py-3 border rounded bg-info bg-opacity-10">
                                    <div class="fs-4 fw-bold text-info">{{ $attendanceSummary['sakit'] ?? 0 }}</div>
                                    <div class="small">Sakit</div>
                                </div>
                            </div>
                            <div class="col-md-2 col-6 mb-3">
                                <div class="text-center py-3 border rounded bg-warning bg-opacity-10">
                                    <div class="fs-4 fw-bold text-warning">{{ $attendanceSummary['izin'] ?? 0 }}</div>
                                    <div class="small">Izin</div>
                                </div>
                            </div>
                            <div class="col-md-2 col-6 mb-3">
                                <div class="text-center py-3 border rounded bg-danger bg-opacity-10">
                                    <div class="fs-4 fw-bold text-danger">{{ $attendanceSummary['alpa'] ?? 0 }}</div>
                                    <div class="small">Alpa</div>
                                </div>
                            </div>
                            <div class="col-md-2 col-6 mb-3">
                                <div class="text-center py-3 border rounded bg-secondary bg-opacity-10">
                                    <div class="fs-4 fw-bold text-secondary">{{ $attendanceSummary['terlambat'] ?? 0 }}</div>
                                    <div class="small">Terlambat</div>
                                </div>
                            </div>
                            <div class="col-md-2 col-6 mb-3">
                                <div class="text-center py-3 border rounded bg-primary bg-opacity-10">
                                    <div class="fs-4 fw-bold text-primary">{{ array_sum($attendanceSummary) }}</div>
                                    <div class="small">Total</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Teacher Attendance List -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Daftar Kehadiran Guru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">No</th>
                                <th style="width: 80px;">Foto</th>
                                <th>Nama Guru</th>
                                <th>NIP</th>
                                <th>Status</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $index => $record)
                            <tr>
                                <td class="align-middle">{{ $index + 1 }}</td>
                                <td class="align-middle">
                                    <img src="{{ $record->teacher->profile_photo_url }}" alt="{{ $record->teacher->name }}" class="rounded-circle" width="40" height="40">
                                </td>
                                <td class="align-middle fw-medium">{{ $record->teacher->name }}</td>
                                <td class="align-middle">{{ $record->teacher->nip ?? '-' }}</td>
                                <td class="align-middle">
                                    @switch($record->status)
                                        @case('hadir')
                                            <span class="badge bg-success">Hadir</span>
                                            @break
                                        @case('izin')
                                            <span class="badge bg-warning">Izin</span>
                                            @break
                                        @case('sakit')
                                            <span class="badge bg-info">Sakit</span>
                                            @break
                                        @case('alpa')
                                            <span class="badge bg-danger">Alpa</span>
                                            @break
                                        @case('terlambat')
                                            <span class="badge bg-secondary">Terlambat</span>
                                            @break
                                        @default
                                            <span class="badge bg-light text-dark">{{ ucfirst($record->status) }}</span>
                                    @endswitch
                                </td>
                                <td class="align-middle">{{ $record->notes ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bx bx-info-circle fs-4 d-block mb-2"></i>
                                    Belum ada data kehadiran guru
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Notes -->
        @if($attendance->notes)
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Catatan Tambahan</h5>
                <p class="mb-0">{{ $attendance->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
