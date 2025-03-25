@extends('layouts.dashboard')

@section('page-title', 'Jadwal Mingguan Kelas')

@section('page-actions')
<div class="d-flex">
    <a href="{{ route('student-schedules.index') }}" class="btn btn-light btn-sm me-2">
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
        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-light border-0">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Pilih Kelas</h6>
                        <form action="{{ route('student-schedules.weekly') }}" method="GET" class="row g-3">
                            <div class="col-md-6">
                                <select class="form-select select2" id="class_id" name="class_id">
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ $class && $class->id == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-filter-alt me-1"></i> Tampilkan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Class Info -->
        @if($class)
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-0 bg-success bg-opacity-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" 
                                     style="width: 64px; height: 64px; font-size: 24px;">
                                    <i class="bx bxs-school"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0">{{ $class->name }}</h5>
                                <p class="text-muted mb-0">{{ $class->level }}</p>
                                <div class="mt-2">
                                    <span class="badge bg-success">{{ array_sum(array_map(function($day) { return count($day); }, $weeklySchedule)) }} jadwal</span>
                                    @if($class->students_count)
                                    <span class="badge bg-primary ms-1">{{ $class->students_count }} siswa</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

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
                                            <div class="schedule-item mb-2 p-2 border rounded bg-success bg-opacity-10">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="badge bg-success">{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</span>
                                                    <a href="{{ route('schedules.show', $schedule) }}" class="text-success">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                </div>
                                                <h6 class="mb-0 text-truncate">{{ $schedule->subject }}</h6>
                                                <div class="small">
                                                    <div class="text-truncate fw-bold">{{ $schedule->teacher ? $schedule->teacher->name : '-' }}</div>
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
    </div>
</div>
@endsection

@section('styles')
<style>
    .schedule-table {
        table-layout: fixed;
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
        background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
        color: white;
        font-weight: 500;
        text-align: center;
        padding: 0.75rem;
    }
    
    .schedule-table th:first-child {
        background: #f8f9fa;
        color: #444;
    }
    
    .schedule-cell {
        height: 140px;
        vertical-align: top;
        padding: 0.5rem;
        overflow-y: auto;
        background-color: #fff;
        transition: background-color 0.2s;
    }
    
    .schedule-cell:hover {
        background-color: #f8f9fa;
    }
    
    .schedule-item {
        font-size: 0.85rem;
        background-color: rgba(40, 167, 69, 0.08);
        border-left: 3px solid #28a745;
        transition: all 0.3s ease;
    }
    
    .schedule-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
    }
    
    .schedule-table th {
        position: sticky;
        top: 0;
        z-index: 1;
    }
    
    /* Time column styling */
    .schedule-table td:first-child {
        font-weight: 500;
        text-align: center;
        background-color: #f8f9fa;
    }
    
    .badge.bg-success {
        background-color: var(--success-color) !important;
    }
</style>
@endsection
