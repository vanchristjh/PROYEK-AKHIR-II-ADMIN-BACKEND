@extends('layouts.dashboard')

@section('page-title', 'Detail Mata Pelajaran')

@section('page-actions')
<div class="d-flex">
    <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-primary btn-sm me-2">
        <i class="bx bx-edit me-1"></i> Edit
    </a>
    <a href="{{ route('subjects.index') }}" class="btn btn-secondary btn-sm">
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
                    <h5 class="card-title mb-0">{{ $subject->name }}</h5>
                    <div>
                        @if($subject->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                        @if($subject->subject_type)
                            <span class="badge bg-info ms-1">{{ $subject->subject_type }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Informasi Dasar</h6>
                        <table class="table table-sm">
                            <tr>
                                <td width="40%">Kode</td>
                                <td>{{ $subject->code ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Tingkat Kelas</td>
                                <td>{{ $subject->class_level ?? 'Semua Tingkat' }}</td>
                            </tr>
                            <tr>
                                <td>Semester</td>
                                <td>{{ $subject->semester ? 'Semester '.$subject->semester : 'Semua Semester' }}</td>
                            </tr>
                            <tr>
                                <td>Kurikulum</td>
                                <td>{{ $subject->curriculum ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Jumlah SKS</td>
                                <td>{{ $subject->credits ?? '0' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Deskripsi</h6>
                        <p class="text-muted mb-0">{{ $subject->description ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h6 class="fw-bold">Guru Pengampu</h6>
                    @if($subject->teachers->count() > 0)
                        <div class="row">
                            @foreach($subject->teachers as $teacher)
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    @if($teacher->profile_photo)
                                                        <img src="{{ asset('storage/'.$teacher->profile_photo) }}" alt="{{ $teacher->name }}" class="rounded-circle" width="48" height="48">
                                                    @else
                                                        <div class="avatar-placeholder bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                            {{ substr($teacher->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-0 fw-semibold">{{ $teacher->name }}</h6>
                                                    <p class="mb-0 small text-muted">{{ $teacher->subject ?? 'Tidak ada mata pelajaran' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Belum ada guru yang mengajar mata pelajaran ini.</p>
                    @endif
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-primary">
                        <i class="bx bx-edit me-1"></i> Edit Mata Pelajaran
                    </a>
                    <form action="{{ route('subjects.destroy', $subject) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus mata pelajaran ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bx bx-trash me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Jadwal Pelajaran</h5>
            </div>
            <div class="card-body">
                @if($subject->schedules && $subject->schedules->count() > 0)
                    <div class="list-group">
                        @foreach($subject->schedules as $schedule)
                            <a href="{{ route('schedules.show', $schedule) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $schedule->class->name }}</h6>
                                    <small>{{ ucfirst($schedule->day_of_week) }}</small>
                                </div>
                                <p class="mb-1 small">{{ $schedule->time_start }} - {{ $schedule->time_end }}</p>
                                <small class="text-muted">{{ $schedule->teacher->name }}</small>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <img src="https://via.placeholder.com/100" alt="No schedules" class="img-fluid mb-3 opacity-50" style="max-width: 100px;">
                        <p class="text-muted mb-0">Belum ada jadwal untuk mata pelajaran ini</p>
                    </div>
                @endif
                
                <div class="mt-3 text-center">
                    <a href="{{ route('schedules.create') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bx bx-plus me-1"></i> Tambah Jadwal
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Informasi Sistem</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">ID</span>
                        <span>{{ $subject->id }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Dibuat pada</span>
                        <span>{{ $subject->created_at->format('d M Y H:i') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Diperbarui pada</span>
                        <span>{{ $subject->updated_at->format('d M Y H:i') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Status</span>
                        <span>
                            @if($subject->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
