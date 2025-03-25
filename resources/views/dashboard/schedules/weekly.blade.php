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
                                <td class="schedule-cell p-2 position-relative" style="min-width: 150px; height: 100px;">
                                    @if(isset($weeklySchedule[$day][$timeSlot]) && count($weeklySchedule[$day][$timeSlot]) > 0)
                                        @foreach($weeklySchedule[$day][$timeSlot] as $schedule)
                                            <div class="schedule-item mb-2 p-2 border rounded bg-light shadow-sm">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="badge bg-primary">{{ $schedule->subject }}</span>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-link text-muted dropdown-toggle p-0" type="button" data-bs-toggle="dropdown">
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
                                                <div class="small">
                                                    <div class="text-truncate fw-medium">{{ $schedule->class ? $schedule->class->name : '-' }}</div>
                                                    <div class="text-muted small">
                                                        <i class="bx bx-time me-1"></i>{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        <i class="bx bx-user me-1"></i>{{ $schedule->teacher ? $schedule->teacher->name : '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center py-2 text-muted small">
                                            <i class="bx bx-calendar-x d-block mb-1 fs-4"></i>
                                            Tidak ada jadwal
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
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }
    
    .schedule-table th, 
    .schedule-table td {
        border: 1px solid #e9ecef;
    }
    
    .schedule-table th {
        background-color: var(--primary-color);
        color: white;
        font-weight: 500;
        text-align: center;
        padding: 0.75rem;
        border-bottom: 2px solid #0055a4;
    }
    
    .schedule-table th:first-child {
        background-color: #f8f9fa;
        color: #444;
        border-bottom: 2px solid #e9ecef;
    }
    
    .schedule-table td {
        padding: 0.75rem;
        vertical-align: top;
        height: 120px;
    }
    
    .schedule-table td:first-child {
        background-color: #f8f9fa;
        font-weight: 500;
        text-align: center;
        width: 100px;
        vertical-align: middle;
    }
    
    .schedule-item {
        background-color: rgba(0, 102, 179, 0.08);
        border-left: 3px solid var(--primary-color);
        border-radius: 0.25rem;
        padding: 0.5rem;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        transition: all 0.2s;
        position: relative;
    }
    
    .schedule-item:hover {
        background-color: rgba(0, 102, 179, 0.15);
        transform: translateY(-2px);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
    }
    
    .schedule-item h6 {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .actions-menu {
        position: absolute;
        top: 0.25rem;
        right: 0.25rem;
        opacity: 0;
        transition: opacity 0.2s;
    }
    
    .schedule-item:hover .actions-menu {
        opacity: 1;
    }
    
    .empty-slot {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
        font-style: italic;
        font-size: 0.9rem;
    }
    
    @media (max-width: 992px) {
        .schedule-table {
            min-width: 992px;
        }
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
