@extends('layouts.dashboard')

@section('page-title', 'Jadwal Mingguan')

@php
    // Define day names mapping for translations
    $dayNames = [
        'monday' => 'Senin',
        'tuesday' => 'Selasa',
        'wednesday' => 'Rabu',
        'thursday' => 'Kamis',
        'friday' => 'Jumat',
        'saturday' => 'Sabtu',
        'sunday' => 'Minggu',
    ];
@endphp

@section('page-actions')
<div class="d-flex">
    <a href="{{ route('schedules.index') }}" class="btn btn-light btn-sm me-2">
        <i class="bx bx-list-ul me-1"></i> Tampilan Daftar
    </a>
    <a href="{{ route('schedules.create') }}" class="btn btn-primary btn-sm">
        <i class="bx bx-plus me-1"></i> Tambah Jadwal
    </a>
</div>
@endsection

@section('dashboard-content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-light border-0">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Filter Jadwal</h6>
                        <form action="{{ route('schedules.weekly') }}" method="GET" class="row g-3">
                            <div class="col-md-6">
                                <label for="class_id" class="form-label">Kelas</label>
                                <select class="form-select" id="class_id" name="class_id">
                                    <option value="">Semua Kelas</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="teacher_id" class="form-label">Guru</label>
                                <select class="form-select" id="teacher_id" name="teacher_id">
                                    <option value="">Semua Guru</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-filter-alt me-1"></i> Filter
                                </button>
                                <a href="{{ route('schedules.weekly') }}" class="btn btn-outline-secondary">
                                    <i class="bx bx-reset me-1"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Weekly Schedule -->
        <div class="table-responsive">
            <table class="table table-bordered schedule-table">
                <thead>
                    <tr class="text-center bg-light">
                        <th scope="col" width="10%">Waktu</th>
                        <th scope="col" width="12.85%">Senin</th>
                        <th scope="col" width="12.85%">Selasa</th>
                        <th scope="col" width="12.85%">Rabu</th>
                        <th scope="col" width="12.85%">Kamis</th>
                        <th scope="col" width="12.85%">Jumat</th>
                        <th scope="col" width="12.85%">Sabtu</th>
                        <th scope="col" width="12.85%">Minggu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($timeSlots as $timeSlot)
                        <tr>
                            <td class="bg-light text-center align-middle fw-bold">{{ $timeSlot }}</td>
                            
                            @foreach($days as $day)
                                <td class="schedule-cell">
                                    @if(isset($weeklySchedule[$day][$timeSlot]) && count($weeklySchedule[$day][$timeSlot]) > 0)
                                        @foreach($weeklySchedule[$day][$timeSlot] as $schedule)
                                            <div class="schedule-item mb-2 p-2 border rounded
                                                @if($schedule->is_active) bg-white @else bg-light text-muted @endif">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="badge bg-primary">{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</span>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm py-0 px-1" type="button" data-bs-toggle="dropdown">
                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="{{ route('schedules.show', $schedule) }}">
                                                                <i class="bx bx-show me-1"></i> Detail
                                                            </a></li>
                                                            <li><a class="dropdown-item" href="{{ route('schedules.edit', $schedule) }}">
                                                                <i class="bx bx-edit me-1"></i> Edit
                                                            </a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="dropdown-item text-danger delete-schedule" href="#" 
                                                                    data-id="{{ $schedule->id }}" 
                                                                    data-subject="{{ $schedule->subject }}">
                                                                <i class="bx bx-trash me-1"></i> Hapus
                                                            </a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <h6 class="mb-0 text-truncate">{{ $schedule->subject }}</h6>
                                                <div class="small">
                                                    <div class="text-truncate">{{ $schedule->class ? $schedule->class->name : '-' }}</div>
                                                    <div class="text-truncate text-muted">{{ $schedule->teacher ? $schedule->teacher->name : '-' }}</div>
                                                    @if($schedule->room)
                                                        <div class="text-truncate text-muted"><i class="bx bx-map-pin"></i> {{ $schedule->room }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center py-2">
                                            <span class="text-muted small">-</span>
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Legend -->
        <div class="mt-4 d-flex justify-content-end">
            <div class="d-flex align-items-center me-3">
                <div class="bg-white border rounded p-1 me-2" style="width: 20px; height: 20px;"></div>
                <span class="small">Jadwal Aktif</span>
            </div>
            <div class="d-flex align-items-center">
                <div class="bg-light border rounded p-1 me-2" style="width: 20px; height: 20px;"></div>
                <span class="small">Jadwal Tidak Aktif</span>
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
                <p>Apakah Anda yakin ingin menghapus jadwal mata pelajaran <span id="scheduleSubject" class="fw-bold"></span>?</p>
                <p class="text-danger small">Tindakan ini tidak dapat dibatalkan.</p>
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

@section('styles')
<style>
    .schedule-table {
        table-layout: fixed;
    }
    
    .schedule-cell {
        height: 140px;
        vertical-align: top;
        padding: 0.5rem;
        overflow-y: auto;
    }
    
    .schedule-item {
        font-size: 0.85rem;
    }
    
    .schedule-table th {
        position: sticky;
        top: 0;
        background-color: #f8f9fa;
        z-index: 1;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete modal
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const deleteForm = document.getElementById('deleteForm');
        const scheduleSubject = document.getElementById('scheduleSubject');
        
        document.querySelectorAll('.delete-schedule').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const scheduleId = this.getAttribute('data-id');
                const subject = this.getAttribute('data-subject');
                
                deleteForm.action = `/schedules/${scheduleId}`;
                scheduleSubject.textContent = subject;
                
                deleteModal.show();
            });
        });
    });
</script>
@endsection
